<style>
    .table-bordered{background: #f2f2f2;}
    .btnADD{background: gold; border: 1px solid black;}
    .btn{border: 1px solid black;}
	.bg-bgcustom {
		background-color: rgb(150,150,150);
	}
</style>
<?php
$student_no = isset($_GET['student_no']) ? $_GET['student_no'] : "none";
$val = isset($_GET['val']) ? $_GET['val'] : "none";
$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : "none";
?>

<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header bg-bgcustom">
            <div class="card-tools ml-4">
                <a class="btnBACK btn-block btn-sm btn-secondary btn-flat" href="./index.php"><i class="fas fa-arrow-left"></i> Back </a>
            </div>
			<?php if($_SESSION['login_type'] == 1): ?>
					<div class="card-tools mr-3">
						<a class="btn btn-block btn-sm btn-danger btn-flat multi-delete-btn"><i class="fas fa-trash"></i> Delete Selected</a>
					</div>
                    <div class="card-tools mr-3">
						<a class="btn btn-block btn-sm btn-success btn-flat multi-restore-btn"><i class="fas fa-trash-restore"></i> Restore Selected</a>
					</div>  
			<?php endif; ?>
			<div>
                <b style='color:white' >Trash Files</b>
                </div>
		</div>
		
		<div class="card-body">
			<table class="table table-hover table-bordered table-sm display nowrap" style="width:100%" id="list">
			    <?php if($_SESSION['login_type'] == 1 ): ?>
				<colgroup>
					<col width="40%">
					<col width="15%">
					<col width="25%">
					<col width="20%">
				</colgroup>
			    <?php else: ?>
				<colgroup>
					<col width="40%">
					<col width="15%">
					<col width="25%">
					<col width="20%">		
				</colgroup>
			    <?php endif; ?>

				<thead>
					<tr>
					<th>Days&nbsp;(30)&nbsp;</th>
						<?php if($_SESSION['login_type'] == 1 ): ?>		
							<th><input type="checkbox" id="multi-delete-checkbox"></th>
						<?php endif; ?>
						<th>File Name &nbsp;</th>
						<th>File Type &nbsp;</th>
						<th>Date Deleted &nbsp;</th>
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

					$query1 = mysqli_query($conn, "SELECT * FROM user_file WHERE file_status = 'deleted'");
					while($user_file = mysqli_fetch_array($query1)):

					?>
					<tr>	
					<td class="days-deleted">
    <?php
        $dateUploaded = new DateTime($user_file['date_uploaded']);
        $currentDate = new DateTime();
        $daysDeleted = $currentDate->diff($dateUploaded)->days;
        echo $daysDeleted;
    ?>
</td>


						<?php if ($_SESSION['login_type'] == 1): ?>
							<td>
								<input type="checkbox" class="file-checkbox" value="<?php echo $user_file['file_id'] . '|' . $user_file['file_name']; ?>">
							</td>
						<?php endif; ?>
						<td><?php echo ucwords(substr($user_file['file_name'], strpos($user_file['file_name'], '_') + 1)) ?></td>
						<td><?php echo ucwords($user_file['file_type']) ?></td>
						<td><?php echo ucwords($user_file['date_uploaded']) ?></td>
						<td class="text-center">
							<div class="btn-group">
                                <button type="button" class="btn btn-success btn-flat restore_file" data-file-id="<?php echo $user_file['file_id']; ?>">
                                    <i class="fas fa-trash-restore"></i>
                                </button>
								<?php if ($user_file['file_type'] == 'pdf'): ?>
									<a href="./index_file.php?page=view_file&file=<?php echo $user_file['file_name']; ?>&student_no=<?php echo $user_file['student_no']; ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id?>&file_type=<?php echo $user_file['file_type']?>&val=trash_files" class="btn btn-info btn-flat">
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
								<button type="button" class="btn btn-danger btn-flat delete_file" data-file-id="<?php echo $user_file['file_id']; ?>" data-student-no="<?php echo $user_file['student_no'];?>" data-file-name="<?php echo $user_file['file_name'];?>">
								<i class="fas fa-trash"></i>	
								</button>
							</div>
						</td>
                    </tr>	
                    <?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- AUTO DELETE AFTER 30 DAYS QUERY -->
<?php
$deleteQuery = "DELETE FROM user_file WHERE file_status = 'deleted' AND date_uploaded <= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$conn->query($deleteQuery);
?>
<!-- AUTO DELETE AFTER 30 DAYS QUERY -->
<script>
	$(document).ready(function(){
		$('#list').dataTable({
			scrollX: true,
		});

		// Handle multi-delete checkbox
        $("#multi-delete-checkbox").click(function(){
            $(".file-checkbox").prop("checked", $(this).prop("checked"));
        });

		// Handle multi-delete form submission
		$(".multi-delete-btn").click(function(){
            var selected_file_ids = [];
            var selected_file_names = [];
            $(".file-checkbox:checked").each(function(){
                var values = $(this).val().split('|');
                selected_file_ids.push(values[0]);
				selected_file_names.push(values[1]);
                console.log(values[0]); // ID value
				console.log(values[1]); // ID value
            });

            if(selected_file_ids.length > 0){
                if(confirm("Are you sure you want to delete the selected file/s?")){
                    delete_multiple_files (selected_file_ids, selected_file_names);
                    start_load()
                }
            }else{
                alert("Please select at least one file to delete.");
            }
        });

		// Function to delete multiple records using AJAX
		function delete_multiple_files(file_ids, file_names) {
            $.ajax({
                url: "ajax.php?action=delete_multiple_files",
                method: "POST",
                data: {
                    delete: true,
                    file_ids: file_ids,
					file_names: file_names,
                },
                success: function(response) {
                    if (response == 1) {
                        alert_toast("Files Successfully Deleted", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response == 2) {
                        alert_toast("Error Deleting Files", 'danger');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }

		// Handle multi-delete form submission
		$(".multi-restore-btn").click(function(){
            var selected_file_ids = [];
            $(".file-checkbox:checked").each(function(){
                var values = $(this).val().split('|');
                selected_file_ids.push(values[0]);
                console.log(values[0]); // ID value
            });

            if(selected_file_ids.length > 0){
                if(confirm("Are you sure you want to delete the selected file/s?")){
                    restore_multiple_files (selected_file_ids);
                    start_load()
                }
            }else{
                alert("Please select at least one file to delete.");
            }
        });

		// Function to delete multiple records using AJAX
		function restore_multiple_files(file_ids) {
            $.ajax({
                url: "ajax.php?action=restore_multiple_files",
                method: "POST",
                data: {
                    delete: true,
                    file_ids: file_ids,
                },
                success: function(response) {
                    if (response == 1) {
                        alert_toast("Files Successfully Deleted", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response == 2) {
                        alert_toast("Error Deleting Files", 'danger');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }

		$('#list').on('click', '.delete_file', function(){
			var file_id = $(this).data('file-id');
			var student_no = $(this).data('student-no');
			var file_name = $(this).data('file-name');
			console.log(file_id, student_no, file_name); // Log the parameter values

			_conf("Are you sure to delete this file?", "delete_file", [file_id, student_no, "'" + file_name + "'"]);
		});

        $('#list').on('click', '.restore_file', function() {
			var file_id = $(this).data('file-id');
			console.log(file_id,); // Log the parameter values

			_conf("Are you sure to restore this file?",
                  "restore_file",
                [file_id]);
		});
	});

	function delete_file(file_id, student_no, file_name) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_file',
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

    function restore_file(file_id){
		start_load()
		$.ajax({
			url:'ajax.php?action=restore_file',
			method:'POST',
			data:{file_id:file_id},
			success:function(resp){
				if(resp==1){
					alert_toast("File Successfully Restored",'success')
					setTimeout(function(){
						location.reload()
					},500)
				}
			}
		})
	}
</script>

