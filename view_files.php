<style>
.card-header{background: #00b300;}
.card-body{background: #FFE9A2;}
.table-bordered{background: #f2f2f2;}
.btnADD{background: #0096FF; border: 1px solid black;}
.btn {border: 1px solid black;}
.btnBACK{border: 1px solid black;}
.card{border: 1px solid black;}

.cardHEADER{border: 1px solid black;}
</style>
<?php
//Passing Data
$student_no = $_GET['student_no']; 
$val = isset($_GET['val']) ? $_GET['val'] : "none";
$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : 1;

//Query for displaying Info
$student_query = $conn->query("SELECT last_name, first_name, middle_name FROM record WHERE id = $student_no");
if ($student_query) {
    $student_row = $student_query->fetch_assoc();
    $last_name = $student_row['last_name'];
    $first_name = $student_row['first_name'];
    $middle_name = $student_row['middle_name'];
    $student_name = $last_name . ', ' . $first_name . ' ' . $middle_name;
} else {
    // Handle the error if the query fails
    $error_message = $conn->error;
    echo "Error retrieving student information: " . $error_message;
    exit;
}

$folder_id_query = $conn->query("SELECT folder_id FROM record WHERE id = $student_no");
if ($folder_id_query) {
    $folder_id_arr = $folder_id_query->fetch_assoc();
    $folder_id = $folder_id_arr['folder_id'];
}

if ($folder_id != 1) {
    $folder_name_query = $conn->query("SELECT folder_name FROM folders WHERE id = $folder_id");
    if ($folder_name_query->num_rows > 0) {
        $folder_row = $folder_name_query->fetch_assoc();
        $folder_name = $folder_row['folder_name'];
    } else {
        // Handle the case when no folder with the given ID is found
        $folder_name = "Unknown folder";
    }
    $folder_name_query->free_result();
} else {
    $folder_name = "Record List";
}

	
?>


<?php if($_SESSION['login_type'] == 1): ?>
		
		<h6 class="mb-1"><strong>Folder: </strong><?php echo $folder_name; ?></h6>
	
		<?php endif; ?>

<br>	<?php
$query1 = mysqli_query($conn, "SELECT * FROM user_file WHERE student_no = '$student_no' AND file_status='notdeleted'");
($user_file = mysqli_fetch_array($query1))
?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">

		<b style='color:black' >Name: &nbsp;</b> <b style='color:white' ><?php echo $student_name;  ?></b> &nbsp; <b style='color:black' >Control No: &nbsp;</b> <b style='color:white' ><?php echo $student_no; ?></b>
			<?php if($val == "record_list"):?>
				<div class="card-tools ml-4">
					<a class="btnBACK btn-block btn-sm btn-secondary btn-flat" href="./index.php?page=record_list"><i class="fas fa-arrow-left"></i> Back </a>
				</div>
			<?php endif; ?>
			<?php if($val == "folder"):?>
				<div class="card-tools ml-4">
					<a class="btnBACK btn-block btn-sm btn-secondary btn-flat" href="./index.php?page=folder&folder_id=<?php echo $folder_id ?>"><i class="fas fa-arrow-left"></i> Back </a>
				</div>
			<?php endif; ?>
			<?php if($val == "trash_record"):?>
				<div class="card-tools ml-4">
					<a class="btnBACK btn-block btn-sm btn-secondary btn-flat" href="./index.php?page=trash_records"><i class="fas fa-arrow-left"></i> Back </a>
				</div>
			<?php endif; ?>
			<!--<?php if($val == "year"):?>
				<div class="card-tools ml-4">
					<a class="btnBACK btn-block btn-sm btn-secondary btn-flat" <?php echo $folder_id ?>"><i class="fas fa-arrow-left"></i> Back </a>
				</div>
			<?php endif; ?>-->
			
			<?php if ($_SESSION['login_type'] == 1): ?>
				<div class="card-tools ">
					<a class="btnADD btn-block btn-sm btn-flat" href="./index.php?page=new_file&student_no=<?php echo $_GET['student_no'] ?>&val=<?php echo $val ?>"><b style='color:white' > Upload</b></a>
				</div>
				<?php endif; ?>
				<?php if ($_SESSION['login_type'] == 1): ?>
					<div class="card-tools mr-3">
						<a class="btn btn-block btn-sm btn-danger btn-flat multi-trash-btn"><i class="fas fa-trash"></i> Delete Selected</a>
					</div>
					<?php endif; ?>
					<div class="card-tools mr-3">
                
                    <a class="btnADD btn-primary btn-block btn-sm btn-flat" href="./index_file.php?page=view_all_files&file=<?php echo $user_file['file_name']; ?>&student_no=<?php echo $user_file['student_no']; ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id?>&file_type=<?php echo $user_file['file_type']?>" class="btn btn-info btn-flat">
                        <i class="fas fa-eye"></i> View All Files
                    </a>
				</div>
		</div> 
		
		
		<div class="card-body">
			<table class="table table-hover table-bordered table-sm display nowrap" style="width:100%" id="list">
			    <?php if($_SESSION['login_type'] == 1 ): ?>
				<colgroup>
					<col width="10%">
					<col width="45%">
					<col width="25%">
					<col width="20%">
				</colgroup>
			    <?php else: ?>
				<colgroup>
					<col width="10%">
					<col width="45%">
					<col width="25%">
					<col width="20%">		
				</colgroup>
			    <?php endif; ?>

				<thead>
					<tr>
					<?php if($_SESSION['login_type'] == 1 ): ?>
							<th><input type="checkbox" id="multi-trash-checkbox"></th>
							<?php endif; ?>
						<th>File Name &nbsp;</th>
						<th>File Type &nbsp;</th>
						<th>Date Uploaded &nbsp;</th>
						<th>Action &nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = '';
					if($_SESSION['login_type'] == 1 ):
					$user = $conn->query("SELECT * FROM users where id in (SELECT clerk_id FROM record) ");
					while($row = $user->fetch_assoc()){
						$uname[$row['id']] = ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
					}
					else:
						$where = " where clerk_id = '{$_SESSION['login_id']}' ";
					endif;

					$query1 = mysqli_query($conn, "SELECT * FROM user_file WHERE student_no = '$student_no' AND file_status='notdeleted'");
					while($user_file = mysqli_fetch_array($query1)):

					?>
					<tr>
					<?php if($_SESSION['login_type'] == 1 ):?>
							<td>
								<input type="checkbox" class="file-checkbox" value="<?php echo $user_file['file_id']?>">
							</td><?php endif; ?>
						
						<td><?php echo ucwords(substr($user_file['file_name'], strpos($user_file['file_name'], '_') + 1)) ?></td>
						<td><?php echo ucwords($user_file['file_type']) ?></td>
						<td><?php echo ucwords($user_file['date_uploaded']) ?></td>
						<td class="text-center">
							<div class="btn-group">

							<?php if ($user_file['file_type'] == 'pdf'): ?>
								<a href="./index_file.php?page=view_file&file=<?php echo $user_file['file_name']; ?>&student_no=<?php echo $user_file['student_no']; ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id?>&file_type=<?php echo $user_file['file_type']?>" class="btn btn-info btn-flat">
									<i class="fas fa-eye"></i>
								</a>
							<?php elseif ($user_file['file_type'] == 'png' || $user_file['file_type'] == 'jpg'): ?>
								<a href="./index_file.php?page=view_file&file=<?php echo $user_file['file_name']; ?>&student_no=<?php echo $user_file['student_no']; ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id?>&file_type=<?php echo $user_file['file_type']?>&file_name=<?php echo $user_file['file_name']?>" class="btn btn-info btn-flat">
									<i class="fas fa-eye"></i>
								</a>
							<?php else: ?>
								<a href="<?php echo 'userfiles/' . $user_file['student_no'] . '/' . $user_file['file_name']; ?>" class="btn btn-info btn-flat">
									<i class="fas fa-eye"></i>
								</a>
							<?php endif; ?>
							<?php if($_SESSION['login_type'] == 1 ): ?>
								<button type="button" class="btn btn-danger btn-flat trash_file" data-file-id="<?php echo $user_file['file_id']; ?>" data-student-no="<?php echo $user_file['student_no'];?>" data-file-name="<?php echo $user_file['file_name'];?>">
								<i class="fas fa-trash"></i>	
								</button><?php endif; ?>
							</div>
						</td>
                    </tr>	
                    <?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var table = $('#list').DataTable({
			scrollX: true,
		});

		// Handle multi-delete checkbox
        $("#multi-trash-checkbox").click(function(){
            $(".file-checkbox").prop("checked", $(this).prop("checked"));
        });

		$('#list').on('click', '.trash_file', function(){
			var file_id = $(this).data('file-id');
			var student_no = $(this).data('student-no');
			var file_name = $(this).data('file-name');
			console.log(file_id, student_no, file_name); // Log the parameter values

			_conf("Are you sure to delete this file?", "trash_file", [file_id, student_no, "'" + file_name + "'"]);
		});

		// Handle multi-delete form submission
        $(".multi-trash-btn").click(function(){
            var selected_file_ids = [];
            $(".file-checkbox:checked").each(function(){
                var values = $(this).val().split('|');
                selected_file_ids.push(values[0]);
                console.log(values[0]); // ID value
            });

            if(selected_file_ids.length > 0){
                if(confirm("Are you sure you want to delete the selected file/s?")){
                    trash_multiple_files (selected_file_ids);
                    start_load()
                }
            }else{
                alert("Please select at least one file to delete.");
            }
        });

		// Function to delete multiple records using AJAX
        function trash_multiple_files(file_ids) {
            $.ajax({
                url: "ajax.php?action=trash_multiple_files",
                method: "POST",
                data: {
                    delete: true,
                    file_ids: file_ids,
                },
                success: function(response) {
                    if (response == 1) {
                        alert_toast("Records Successfully Deleted", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response == 2) {
                        alert_toast("Error Deleting Records", 'danger');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
		
	});
	function goBack() {
            history.back();
        }

	function trash_file(file_id, student_no, file_name) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=trash_file',
			method: 'POST',
			data: {file_id: file_id, student_no: student_no, file_name: "'" + file_name.replace(/'/g, "\\'") + "'"}, // Escape single quotes in file_name
			success: function(resp) {
				if (resp == 1) {
					alert_toast("File Successfully Deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				}
			}
		});
	}
</script>

