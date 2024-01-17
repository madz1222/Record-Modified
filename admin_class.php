<?php
session_start();
ini_set('display_errors', 1);

require 'assets/plugins/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function check_session() {
		if (!isset($_SESSION['login_id'])) {
			header('Location: login.php');
			exit();
		} else {
			$query = "SELECT user_session_id FROM users WHERE id = ?";
			$stmt = $this->db->prepare($query);
			$stmt->bind_param('s', $_SESSION['login_id']);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
	
			if (!$row || $_SESSION['user_session_id'] !== $row['user_session_id']) {
				session_destroy();
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
			
				// Redirect to the login page
				header("location: login.php");
				return 1;
			}
			$stmt->close();
		}
	}
	
	function login() {
		extract($_POST);
		$stmt = $this->db->prepare("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			if (password_verify($password, $user['password'])) {
				foreach ($user as $key => $value) {
					if ($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				
				// Generate MD5 hash for user session ID
				$user_session_id = md5(uniqid());
				$_SESSION['user_session_id'] = $user_session_id;
				// Update the 'is_logged_in' column to 1 and set the 'user_session_id'
				$updateStmt = $this->db->prepare("UPDATE users SET is_logged_in = 1, user_session_id = ? WHERE email = ?");
				$updateStmt->bind_param("ss", $user_session_id, $email);
				$updateStmt->execute();

				return 1; // Login successful
			}
		}

		return 3; // Invalid credentials
	}

	function logout() {
		// Update the 'is_logged_in' column to 0
		$email = $_SESSION['login_email'];
		$updateStmt = $this->db->prepare("UPDATE users SET is_logged_in = 0 WHERE email = ?");
		$updateStmt->bind_param("s", $email);
		$updateStmt->execute();
	
		// Destroy the session and unset session variables
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
	
		// Redirect to the login page
		header("location: login.php");
	}
	

	function generateStudentNo() {
        $query = "SELECT MIN(student_no + 1) AS student_no
            FROM (
              SELECT student_no FROM record
              UNION ALL
              SELECT student_no FROM user_file
            ) AS combined
            WHERE (student_no + 1) NOT IN (
              SELECT student_no FROM record
              UNION ALL
              SELECT student_no FROM user_file
            );";
        $result = mysqli_query($this->db, $query);
        $arrstudent_no = mysqli_fetch_assoc($result);
    
        $student_no = $arrstudent_no['student_no']; // Assign the value to the variable
    
        return $student_no;
    }

	function new_student()
{
    extract($_POST);

    $folder_id = $_POST['folder_id'];
    $user_role = $_SESSION['login_type'];
    $clerk_id = ($user_role === 1) ? $_POST['clerk_id'] : $_SESSION['login_id'];
    $valid_ext = array('pdf', 'txt', 'doc', 'docx', 'ppt', 'zip');
    $fileCount = count($_FILES['files']['name']);

    // Check for duplicate entry
    $checkQuery = "SELECT * FROM record WHERE LOWER(first_name) = LOWER(?) AND LOWER(last_name) = LOWER(?) AND LOWER(middle_name) = LOWER(?)";
    $stmt = $this->db->prepare($checkQuery);
    $stmt->bind_param("sss", $first_name, $last_name, $middle_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return 2; // Duplicate entry found
        exit;
    }

    // Format name values
    $last_name_formatted = ucwords(strtolower($last_name));
    $first_name_formatted = ucwords(strtolower($first_name));
    $middle_name_formatted = ucwords(strtolower($middle_name));

    $record_status = "notdeleted";

    if ($course_name == "Other") {
        $course_name = $other_course;
    }

    // Insert the record into the database
    $insertQuery = "INSERT INTO record (clerk_id, folder_id, first_name, last_name, middle_name, course_name, year_graduate, year_entry, grad_hd, record_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($insertQuery);
    $stmt->bind_param("iissssssss", $clerk_id, $folder_id, $first_name_formatted, $last_name_formatted, $middle_name_formatted, $course_name, $year_graduate, $year_entry, $grad_hd, $record_status);
    $stmt->execute();

    $student_no = mysqli_insert_id($this->db);

    // Iterate through each uploaded file
    for ($i = 0; $i < $fileCount; $i++) {
        $file_name = strtotime(date('y-m-d H:i:s')) . '_' . $_FILES['files']['name'][$i];
        $file_type = $_FILES['files']['type'][$i];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_temp = $_FILES['files']['tmp_name'][$i];
        $location = "userfiles/" . $student_no . "/" . $file_name;
        $date = date("Y-m-d, h:i A", strtotime("+6 HOURS"));
        $file_status = "notdeleted";

        if (!file_exists("userfiles/" . $student_no)) {
            mkdir("userfiles/" . $student_no);
        }

        move_uploaded_file($file_temp, $location);

        if (!empty($file_ext)) {
            $stmt = $this->db->prepare("INSERT INTO user_file (student_no, clerk_id, file_name, file_type, date_uploaded, file_status, folder_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissssi", $student_no, $clerk_id, $file_name, $file_ext, $date, $file_status, $folder_id);
            $stmt->execute();
        }
    }

    return 1; // Record saved successfully
}

	
	function import_excel() {
        extract($_POST);
        $location = "userfiles/";
		ini_set('memory_limit', '1024M');
    
        if (isset($_FILES['importfile']['tmp_name'])) {
            $file = $_FILES['importfile']['tmp_name'];
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = [];
    
            // Define the column indexes for each field
            $columnIndexes = [
                'First_Name' => 'A',
                'Middle_Name' => 'B',
                'Last_Name' => 'C',
                'Course_Name' => 'D',
                'Year_Entry' => 'E',
                'Year_Graduated' => 'F',
				'Grad_HD' => 'G',
                'Clerk' => 'H'
            ];
    
            // Get the starting row and column indexes
            $startRow = 3;  // Start reading from row 3
            $startColumn = 'A';  // Start reading from column A
    
            // Process the data from the Excel file
            for ($rowIndex = $startRow;; $rowIndex++) {
                $rowData = [];
    
                foreach ($columnIndexes as $field => $column) {
                    $cellValue = $worksheet->getCell($column . $rowIndex)->getValue();
                    $rowData[$field] = $cellValue;
                }
    
                // Check if all the values are empty, indicating the end of the data
                if (empty(array_filter($rowData))) {
                    break;
                }
    
                $data[] = $rowData;
            }
    
            // Process each row of data
            foreach ($data as $row) {	
                $first_name = $row['First_Name'];
                $middle_name = $row['Middle_Name'];
                $last_name = $row['Last_Name'];
                $course_name = $row['Course_Name'];
                $year_entry = $row['Year_Entry'];
                $year_graduated = $row['Year_Graduated'];
				$grad_hd = $row['Grad_HD'];

				if ($_SESSION['login_type'] == 1) {
					$clerk_id = $row['Clerk']; // Admin can specify the clerk ID from the Excel file
				} else if ($_SESSION['login_type'] == 2){
					$clerk_id = $_SESSION['login_id']; // Clerk can only use their own ID
				}
                
    
                $last_name_words = explode(' ', $last_name);
                $first_name_words = explode(' ', $first_name);
                $middle_name_words = explode(' ', $middle_name);
    
                $last_name_formatted = implode(' ', array_map(function ($word) {
                    return ucfirst(strtolower($word));
                }, $last_name_words));
    
                $first_name_formatted = implode(' ', array_map(function ($word) {
                    return ucfirst(strtolower($word));
                }, $first_name_words));
    
                $middle_name_formatted = implode(' ', array_map(function ($word) {
                    return ucfirst(strtolower($word));
                }, $middle_name_words));

				// Check if the entry already exists in the database
				$stmt = $this->db->prepare("SELECT COUNT(*) as count FROM record WHERE first_name = ? AND middle_name = ? AND last_name = ?");
				$stmt->bind_param("sss", $first_name_formatted, $middle_name_formatted, $last_name_formatted);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				$count = $row['count'];
	
				if ($count > 0) {
					return 2; // Duplicate entry found
				}

				if(!isset($_POST['folder_id'])) {
					$folder_id = 1;
				} else {
					$folder_id = $_POST['folder_id'];
				}

				$file_status = "notdeleted";
				$record_status = "notdeleted";
    
                $stmt = $this->db->prepare("INSERT INTO record (folder_id, clerk_id, first_name, last_name, middle_name, course_name, year_graduate, year_entry, grad_hd, record_status) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param("iissssssss", $folder_id, $clerk_id, $first_name_formatted, $last_name_formatted, $middle_name_formatted, $course_name, $year_graduated, $year_entry, $grad_hd, $record_status);
				$stmt->execute();
    
                if ($stmt) {
                }
            }
			return 1; // Success
        }
    }
	
	function export_excel() {
		// SQL query to retrieve records
		if ($_SESSION['login_id'] == 1) {
			$sql = "SELECT * FROM record WHERE record_status = 'notdeleted'";
		} else {
			$sql = "SELECT * FROM record WHERE clerk_id = '{$_SESSION['login_id']}' AND record_status ='notdeleted'";
		}
	
		// Execute the query and retrieve the records using $this->db instead of $conn
		$result = $this->db->query($sql);
	
		if ($result->num_rows > 0) {
			// Create an array to store the records
			$records = array();
	
			// Loop through the result set
			while ($row = $result->fetch_assoc()) {
				// Exclude "record_status" and "folder_id" columns from the row data
				unset($row['record_status']);
				unset($row['folder_id']);
				unset($row['clerk_id']);
	
				// Add each row to the records array
				$records[] = $row;
			}
	
			// Create a new spreadsheet object
			$spreadsheet = new Spreadsheet();
	
			// Set the active sheet
			$sheet = $spreadsheet->getActiveSheet();
	
			// Set the headers (column names)
			$columnNames = array_keys($records[0]);
			$columnIndex = 1;
			$columnName = [
				'First_Name' => 'A',
				'Middle_Name' => 'B',
				'Last_Name' => 'C',
				'Course_Name' => 'D',
				'Year_Entry' => 'E',
				'Year_Graduated' => 'F',
				'Grad_HD' => 'G',
				'Clerk' => 'H',
				'Clerk' => 'I'
			];
			foreach ($columnNames as $columnName) {
				$sheet->setCellValueByColumnAndRow($columnIndex, 1, $columnName);
				$columnIndex++;
			}
	
			// Set the data rows
			$rowIndex = 2;
			foreach ($records as $record) {
				$columnIndex = 1;
				foreach ($record as $value) {
					$sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
					$columnIndex++;
				}
				$rowIndex++;
			}
	
			$filename = 'exported_RecordManagementSystem_' . strtotime(date('Y-m-d H:i:s')) . '.xlsx';
	
			// Set the appropriate headers for file download
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. $filename .'"');
			header('Cache-Control: max-age=0');
	
			// Save the spreadsheet as an Excel file and output it directly to the browser
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
	
			exit; // Stop further execution after sending the file
		} else {
			echo 'Failed to export';
		}
		return 1;
	}	

	function export_excel_folder() {
		$folder_id = $_GET['folder_id'];
	
		if ($_SESSION['login_id'] == 1) {
			$sql = "SELECT * FROM record WHERE folder_id = '{$folder_id}'";
		} else {
			$sql = "SELECT * FROM record WHERE clerk_id = '{$_SESSION['login_id']}' AND folder_id = '{$folder_id}'";
		}        
	
		// Execute the query and retrieve the records using $this->db instead of $conn
		$result = $this->db->query($sql);
	
		if ($result->num_rows > 0) {
			// Create an array to store the records
			$records = array();
	
			// Loop through the result set
			while ($row = $result->fetch_assoc()) {
				// Exclude "record_status" and "folder_id" columns from the row data
				unset($row['record_status']);
				unset($row['folder_id']);
	
				// Add each row to the records array
				$records[] = $row;
			}
	
			// Create a new spreadsheet object
			$spreadsheet = new Spreadsheet();
	
			// Set the active sheet
			$sheet = $spreadsheet->getActiveSheet();
	
			// Set the headers (column names)
			$columnNames = array_keys($records[0]);
			$columnIndex = 1;
			foreach ($columnNames as $columnName) {
				$sheet->setCellValueByColumnAndRow($columnIndex, 1, $columnName);
				$columnIndex++;
			}
	
			// Set the data rows
			$rowIndex = 2;
			foreach ($records as $record) {
				$columnIndex = 1;
				foreach ($record as $value) {
					$sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
					$columnIndex++;
				}
				$rowIndex++;
			}
	
			$filename = 'exported_RecordManagementSystem_' . strtotime(date('Y-m-d H:i:s')) . '.xlsx';
	
			// Set the appropriate headers for file download
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. $filename .'"');
			header('Cache-Control: max-age=0');
	
			// Save the spreadsheet as an Excel file and output it directly to the browser
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
	
			exit; // Stop further execution after sending the file
		} else {
			echo 'Failed to Export';
		}
		return 1;
	}	

	function export_excel_year() {
		$course = $_GET['course'];
		$year = $_GET['year'];
	
		if ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 3) {
			$sql = "SELECT * FROM record WHERE course_name = '$course' AND year_entry ='$year'";
		} else {
			$sql = "SELECT * FROM record WHERE clerk_id = '{$_SESSION['login_id']}' AND course = '{$course}'";
		}        	
	
		// Execute the query and retrieve the records using $this->db instead of $conn
		$result = $this->db->query($sql);
	
		if ($result->num_rows > 0) {
			// Create an array to store the records
			$records = array();
	
			// Loop through the result set
			while ($row = $result->fetch_assoc()) {
				// Exclude "record_status" and "folder_id" columns from the row data
				unset($row['record_status']);
				unset($row['folder_id']);
				unset($row['clerk_id']);
				// Add each row to the records array
				$records[] = $row;
			}
	
			// Create a new spreadsheet object
			$spreadsheet = new Spreadsheet();
	
			// Set the active sheet
			$sheet = $spreadsheet->getActiveSheet();
	
			// Set the headers (column names)
			$columnNames = array_keys($records[0]);
			$columnIndex = 1;
			foreach ($columnNames as $columnName) {
				$sheet->setCellValueByColumnAndRow($columnIndex, 1, $columnName);
				$columnIndex++;
			}
	
			// Set the data rows
			$rowIndex = 2;
			foreach ($records as $record) {
				$columnIndex = 1;
				foreach ($record as $value) {
					$sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
					$columnIndex++;
				}
				$rowIndex++;
			}
	
			$filename = 'exported_RecordManagementSystem_' . strtotime(date('Y-m-d H:i:s')) . '.xlsx';
	
			// Set the appropriate headers for file download
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="'. $filename .'"');
			header('Cache-Control: max-age=0');
	
			// Save the spreadsheet as an Excel file and output it directly to the browser
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
	
			exit; // Stop further execution after sending the file
		} else {
			echo 'Failed to Export';
		}
		return 1;
	}	

	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password')
					$v = password_hash($v, PASSWORD_DEFAULT);
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
	
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if($_FILES['img']['tmp_name'] != ''){
			$fname = time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";
		}
	
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users SET $data");
		}else{
			$save = $this->db->query("UPDATE users SET $data WHERE id = $id");
		}
	
		if($save){
			return 1;
		}
	}
	
	function update_user() {
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table')) && !is_numeric($k) && $v !== '') {
				if ($k == 'password') {
					$v = password_hash($v, PASSWORD_DEFAULT);
				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = time() . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		$check = $this->db->query("SELECT * FROM users WHERE email ='$email'" . (!empty($id) ? " AND id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2; // Email already exists
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users SET $data");
		} else {
			$save = $this->db->query("UPDATE users SET $data WHERE id = $id");
		}
		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$_SESSION['login_avatar'] = $fname;
			}
			return 1; // Success
		}
	}
	
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function upload_file(){
		extract($_FILES['file']);
		// var_dump($_FILES);
		if($tmp_name != ''){
				$fname = strtotime(date('y-m-d H:i')).'_'.$name;
				$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
		}
		if(isset($move) && $move){
			return json_encode(array("status"=>1,"fname"=>$fname));
		}
	}//Not Used 

	function remove_file(){
		extract($_POST);
		if(is_file('assets/uploads/'.$fname))
			unlink('assets/uploads/'.$fname);
		return 1;
	}//Not Used 
	
	function delete_file() {
		extract($_POST);
	
		// Remove the single quotes from the file name
		$file_name = str_replace("'", '', $file_name);
	
		$location = "userfiles/" . $student_no . "/" . $file_name;
	
		if (is_file($location)) {
			unlink($location);
		}
		
		$delete = $this->db->query("DELETE FROM user_file WHERE file_id = " . $file_id);
		if ($delete) {
			return 1; 
		}
	}
	
	function save_upload(){
		extract($_POST);
		// var_dump($_FILES);
		$data = " title ='$title' ";
		$data .= ", description ='".htmlentities(str_replace("'","&#x2019;",$description))."' ";
		$data .= ", clerk_id ='{$_SESSION['login_id']}' ";
		$data .= ", file_json ='".json_encode($fname)."' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO record set $data ");
		}else{
			$save = $this->db->query("UPDATE record set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}//Not Used 

	function update_record() {
		extract($_POST);
	
		$update = $this->db->query("UPDATE record SET 
		
		clerk_id = '$clerk_id',
		id = '$id', 
		first_name = '$first_name', 
		last_name = '$last_name', 
		middle_name = '$middle_name', 
		course_name = '$course_name', 
		year_graduate = '$year_graduate', 
		year_entry = '$year_entry', 
		grad_hd = '$grad_hd' 
		WHERE id = '$id'");

		if ($update) {
		return 1;
		}

	}
	
	function delete_record() {
		extract($_POST);
		$deleteRecord = $this->db->query("DELETE FROM record WHERE id = " . $id);
		$deleteUserFile = $this->db->query("DELETE FROM user_file WHERE student_no = " . $id);
	
		$location = "userfiles/" . $id;
	
		if (is_dir($location)) {
			// Remove all files and subdirectories within the directory
			$files = glob($location . '/*');
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
	
			// Delete the directory itself
			rmdir($location);
		}
		
		if ($deleteRecord && $deleteUserFile) {
			return 1;
		}
	}	

	function trash_record() {
		extract($_POST);
	
		// Assuming you have the necessary DB query code here
		$query = "UPDATE record SET record_status = 'deleted' WHERE id = $id";
	
		$trash_record = $this->db->query($query);
	
		if ($trash_record) {
			return 1; // Return 1 if the update was successful
		}
	}

	function trash_file() {
		extract($_POST);
	
		// Assuming you have the necessary DB query code here
		$query = "UPDATE user_file SET file_status = 'deleted' WHERE file_id = $file_id";
	
		$trash_file = $this->db->query($query);
	
		if ($trash_file) {
			return 1; // Return 1 if the update was successful
		}
	}
	
	function restore_record() {
		extract($_POST);
	
		// Assuming you have the necessary DB query code here
		$query = "UPDATE record SET record_status = 'notdeleted' WHERE id = $id";
	
		$trash_record = $this->db->query($query);
	
		if ($trash_record) {
			return 1; // Return 1 if the update was successful
		}
	}

	function restore_file() {
		extract($_POST);
	
		// Assuming you have the necessary DB query code here
		$query = "UPDATE user_file SET file_status = 'notdeleted' WHERE file_id = $file_id";
	
		$trash_file = $this->db->query($query);
	
		if ($trash_file) {
			return 1; // Return 1 if the update was successful
		}
	}

	function delete_folder_second() {
		extract($_POST);
		$deleteRecord = $this->db->query("DELETE FROM folders WHERE id = " . $folder_id);
	
		if ($deleteRecord) {
			return 1;
		}
	}	

	function delete_folder() {
		$folderPath = 'adminfolder';
	
		if (!is_dir($folderPath)) {
			return; // Exit if the folder doesn't exist
		}
	
		$files = glob($folderPath . '/*'); // Get all files and folders inside the "adminfolder"
	
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file); // Delete individual files
			} elseif (is_dir($file)) {
				delete_folder($file); // Recursively delete sub-folders and their contents
			}
		}
	
		rmdir($folderPath); // Remove the empty folder
	}
	
	
	function approve_record() {
		extract($_POST);
	
		$update = $this->db->query("UPDATE record SET 
		record_status = '$approve_record'
		WHERE id = '$id'");

		if ($update) {
		return 1;
		}

	}
	
	function reset_password() {
		extract($_POST);
	
		$reset = $this->db->query("UPDATE users SET
		token = '$token'
		WHERE email = '$email'");
		if ($reset) {
			return 1;
		}
		
	}
	
	function new_file() {
		extract($_POST);
	
		$clerk_id = $_SESSION['login_id'];
		$valid_ext = array('pdf', 'txt', 'doc', 'docx', 'ppt', 'zip');
	
		// Get the total number of files uploaded
		$fileCount = count($_FILES['files']['name']);
	
		// Generate a timestamp for the files
		$timestamp = strtotime(date('Y-m-d H:i:s'));
	
		// Iterate through each uploaded file
		for ($i = 0; $i < $fileCount; $i++) {
			$file_name = $timestamp . '_' . $_FILES['files']['name'][$i]; // Add the same timestamp for all files
			$file_type = $_FILES['files']['type'][$i];
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$file_temp = $_FILES['files']['tmp_name'][$i];
			$location = "userfiles/" . $student_no . "/" . $file_name;
			$date = date("Y-m-d, h:i A", strtotime("+6 HOURS"));
			$file_status = "notdeleted";
	
			if (!file_exists("userfiles/" . $student_no)) {
				mkdir("userfiles/" . $student_no);
			}
	
			move_uploaded_file($file_temp, $location);
	
			$insertfile = $this->db->query("INSERT INTO user_file (student_no, clerk_id, file_name, file_type, date_uploaded, file_status) 
				VALUES ('$student_no', $_SESSION[login_id], '$file_name', '$file_ext', '$date', '$file_status')");
		}
	
		return 1;
	}
	
	function trash_multiple_records() {
		extract($_POST);
		$selectedRecordIds = $_POST['record_ids'];
	
		// Process the selected record IDs
		$successCount = 0; // Initialize the success count variable
		foreach($selectedRecordIds as $recordId) {
			// Perform the deletion operation for each record
			$sql = "UPDATE record SET record_status = 'deleted' WHERE id = $recordId";
			$result = $this->db->query($sql);
	
			// Check if the deletion was successful
			if ($result === TRUE) {
				$successCount++; // Increment the success count
			} else {
				return 2;
			}
		}
	
		if ($successCount > 0) {
			return 1; // Return 1 if at least one record was successfully deleted
		}
	}

	function delete_multiple_records() {
		extract($_POST);
		$selected_record_ids = $_POST['record_ids'];
	
		// Process the selected record IDs
		$successCount = 0; // Initialize the success count variable
		foreach($selected_record_ids as $key => $record_id) {
	
			// Delete related user files for each student_no
			$deleteUserFile = $this->db->query("DELETE FROM user_file WHERE file_id = " . $record_id);
			$location = "userfiles/" . $record_id;
	
			if (is_dir($location)) {
				// Remove all files and subdirectories within the directory
				$files = glob($location . '/*');
				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					}
				}
	
				// Delete the directory itself
				rmdir($location);
			}
	
			// Perform the deletion operation for each record
			$sql = "DELETE FROM record WHERE id = '$record_id'";
			$result = $this->db->query($sql);
	
			// Check if the deletion was successful
			if ($result === TRUE) {
				$successCount++; // Increment the success count
			} else {
				return 2;
			}
		}
	
		if ($successCount > 0) {
			return 1; // Return 1 if at least one record was successfully deleted
		}
	}	

	function restore_multiple_records() {
		extract($_POST);
		$selected_record_ids = $_POST['record_ids'];
	
		// Process the selected record IDs
		$successCount = 0; // Initialize the success count variable
		foreach($selected_record_ids as $record_id) {
			// Perform the deletion operation for each record
			$sql = "UPDATE record SET record_status = 'notdeleted' WHERE id = $record_id";
			$result = $this->db->query($sql);
	
			// Check if the deletion was successful
			if ($result === TRUE) {
				$successCount++; // Increment the success count
			} else {
				return 2;
			}
		}
	
		if ($successCount > 0) {
			return 1; // Return 1 if at least one record was successfully deleted
		}
	}

	function trash_multiple_files() {
		extract($_POST);
		$selected_file_ids = $_POST['file_ids'];
	
		// Process the selected record IDs
		$successCount = 0; // Initialize the success count variable
		foreach($selected_file_ids as $file_id) {
			// Perform the deletion operation for each record
			$sql = "UPDATE user_file SET file_status = 'deleted' WHERE file_id = $file_id";
			$result = $this->db->query($sql);
	
			// Check if the deletion was successful
			if ($result === TRUE) {
				$successCount++; // Increment the success count
			} else {
				return 2;
			}
		}
	
		if ($successCount > 0) {
			return 1; // Return 1 if at least one record was successfully deleted
		}
	}

	function delete_multiple_files() {
		extract($_POST);
		$selected_file_ids = $_POST['file_ids'];
		$selected_file_names = $_POST['file_names'];
	
		// Process the selected record IDs
		$successCount = 0; // Initialize the success count variable
		foreach($selected_file_ids as $key => $file_id) {
			$file_name = $selected_file_names[$key];

			// Perform the deletion operation for each record
			$sql = "DELETE FROM user_file WHERE file_id = '$file_id'";
			$result = $this->db->query($sql);

			$location = "userfiles/" . $file_id . "/" . $file_name;
	
			if (is_file($location)) {
				unlink($location);
			}

			// Check if the deletion was successful
			if ($result === TRUE) {
				$successCount++; // Increment the success count
			} else {
				return 2;
			}
		}
	
		if ($successCount > 0) {
			return 1; // Return 1 if at least one record was successfully deleted
		}
	}	
	
	function restore_multiple_files() {
		extract($_POST);
		$selected_file_ids = $_POST['file_ids'];
	
		// Process the selected record IDs
		$successCount = 0; // Initialize the success count variable
		foreach($selected_file_ids as $file_id) {
			// Perform the deletion operation for each record
			$sql = "UPDATE user_file SET file_status = 'notdeleted' WHERE file_id = $file_id";
			$result = $this->db->query($sql);
	
			// Check if the deletion was successful
			if ($result === TRUE) {
				$successCount++; // Increment the success count
			} else {
				return 2;
			}
		}
	
		if ($successCount > 0) {
			return 1; // Return 1 if at least one record was successfully deleted
		}
	}
}
