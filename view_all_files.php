<?php
// Retrieve the student_no and folder_id from the query parameters
$student_no = $_GET['student_no'];
$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : "none";
$val = isset($_GET['val']) ? $_GET['val'] : "none";

$student_query = $conn->query("SELECT last_name, first_name, middle_name, course_name, year_entry, year_graduate, grad_hd FROM record WHERE id = $student_no");
if ($student_query) {
    $student_row = $student_query->fetch_assoc();
    $last_name = $student_row['last_name'];
    $first_name = $student_row['first_name'];
    $middle_name = $student_row['middle_name'];
    $course_name = $student_row['course_name'];
    $year_entry = $student_row['year_entry'];
    $year_graduate = $student_row['year_graduate'];
    $grad_hd = $student_row['grad_hd'];
    $student_name = $last_name . ', ' . $first_name . ' ' . $middle_name;
} else {
    // Handle the error if the query fails
    $error_message = $conn->error;
    echo "Error retrieving student information: " . $error_message;
    exit;
}

// Check if the user is logged in as type 1 (login type for success) or type 2 (login type for clerk)
if ($_SESSION['login_type'] != 1 && $_SESSION['login_type'] != 2 && $_SESSION['login_type'] != 3) {
    // Redirect the user if not logged in correctly
    header("Location: index.php?page=record_list");
    exit();
}







$files = glob("userfiles/{$student_no}/*.pdf");



?>





<html>
<head>
    <title>View Files</title>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
        }

        .pdf-wrapper {
            width: 330px;
            height: 500px;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .pdf-wrapper embed {
            width: 100%;
            height: 90%;
        }

        .pdf-title {
            margin-top: 10px;
            text-align: center;
            font-size: 12px;
            align-items: flex-start;
        }

        .back-link {
            font-size: 12px;
            margin: 10px;
            float: right;
        }

        .print-button {
            font-size: 12px;
            margin: 15px;
            float: right;
        }
    </style>
</head>

<?php if ($_SESSION['login_type'] == 1): ?>
<a href="./index.php?page=view_files&student_no=<?php echo $student_no ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id ?>"
   class="btn btn-secondary back-link">
    <i class="fas fa-arrow-left"></i>
    Back
    
</a>
<?php endif; ?>
<?php  if ($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 3):?>
   <button class="btn btn-secondary back-link" onclick="goBack()">
    <i class="fas fa-arrow-left"></i>
    Back
    </button>
    <?php endif; ?>
    <a class="btn btn-primary back-link writereport" href="javascript:void(0)" data-id="<?php echo $student_no; ?>">
        <i class="fa fa-plus"></i> Write Report
    </a>


    <style>
  table {
    border-collapse: collapse;
    width: 100%;
  }
  
  th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
  
  th {
    background-color: #f0f0f0;
  }
</style>

<table>
  <tr>
    <th>Name:</th>
    <td><?php echo $student_name; ?></td>
  </tr>
  <tr>
    <th>Control No:</th>
    <td><?php echo $student_no; ?></td>
  </tr>
  <tr>
    <th>Course:</th>
    <td><?php echo $course_name; ?></td>
  </tr>
  <tr>
    <th>Year Entry:</th>
    <td><?php echo $year_entry; ?></td>
  </tr>
  <tr>
    <th>Year Graduate:</th>
    <td><?php echo $year_graduate; ?></td>
  </tr>
  <tr>
    <th>Status:</th>
    <td><?php echo $grad_hd; ?></td>
  </tr>
</table>
<br><br>
    <?php if (!empty($files)): ?>
        <div class="container">
            <?php foreach ($files as $file): ?>
                <?php
                // Get the filename without the numbering
                $fileNameWithoutNumber = preg_replace('/\d+/', '', basename($file));
                ?>
                <div class="pdf-wrapper">
                   <!-- <input type="checkbox" class="file-checkbox" name="selected_files[]" value="<?php echo $file; ?>"
                           id="<?php echo $fileNameWithoutNumber; ?>"/>-->
                    <div class="pdf-title"><?php echo $fileNameWithoutNumber; ?></div>
                    <embed src="<?php echo $file; ?>" type="application/pdf"/>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?><center><br><br><br><br><br>
        <p>No files uploaded for this student.</p></center>
    <?php endif; ?>
</form>
<script>
function goBack() {
            history.back();
        }
        $('.writereport').click(function () {
    uni_modal('Write Report', 'writereport.php?student_no=' + $(this).attr('data-id'));
});

// Set background color
$('.modal-content').css('background-color', '#00FF00');
</script>
</body>
</html>
