<script>
    var selectedFolderId = 0;
</script>
<style>
    .card-header{background: #00b300;}
    .card-body{background: #FFE9A2;}
    .table-bordered{background: #f2f2f2;}
    .btnADD{background: gold; border: 1px solid black;}
    .btn{border: 1px solid black;}
    .card{border: 1px solid black;}
</style>

<?php 
//Passing Data
$val = "folder";
$folder_id = $_GET['folder_id']; 

// Retrieve the folder name from the database
$folder_query = $conn->query("SELECT folder_name FROM folders WHERE id = $folder_id");
if ($folder_query) {
    $folder_row = $folder_query->fetch_assoc();
    $folder_name = $folder_row['folder_name'];
} else {
    exit;
}
?>

		<b> Folder Name: </b><?php echo $folder_name; ?>
        <br><br>

<form method="POST" action="" id="multi-delete-form">
    <div class="col-sm-12">
        <div class="card card-outline">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-secondary btn-flat" href="./index.php?page=manage_folders"><i class="fas fa-arrow-left"></i> Back </a>
                </div>
                <div class="card-tools mr-3">
                    <a class="btn btn-block btn-sm btn-warning btn-flat" href="./index.php?page=folder_new_student_record&folder_id=<?php echo $folder_id; ?>"><i class="fa fa-plus"></i> Add New</a>
                </div>
                <div class="card-tools mr-3">
                    <a class="btn btn-block btn-sm btn-info btn-flat" href="./index.php?page=import_excel&folder_id=<?php echo $folder_id; ?>"><i class="fa fa-plus"></i> Import Excel</a>
                </div>	
                <div class="card-tools mr-3">
                    <a class="btn btn-block btn-sm btn-info btn-flat export_excel" href="ajax.php?action=export_excel_folder&folder_id=<?php echo $folder_id; ?>"><i class="fa fa-download"></i> Export Excel</a>
                </div>
				<?php if($_SESSION['login_type'] == 1): ?>
					<div class="card-tools mr-3">
						<a class="btn btn-block btn-sm btn-danger btn-flat multi-delete-btn"><i class="fas fa-trash"></i> Delete Selected</a>
					</div>
				<?php endif; ?>
			<!--		<?php if($_SESSION['login_type'] == 1): ?>
			<div class="filter">
					<label for="filter-select">Filter Data:</label>
					<select id="filter-select">
						<option value="0">All</option>
						<?php
						$clerkIds = array(); // Initialize an empty array
						$user = $conn->query("SELECT * FROM users WHERE id IN (SELECT clerk_id FROM record)");
						while($row = $user->fetch_assoc()){
							$clerkIds[] = $row['id']; // Add each clerk ID to the array
							$clerkName = "Clerk " . $row['id'];
							echo '<option value="' . $row['id'] . '">' . $clerkName . '</option>';
						}
						?>
					</select>
				</div>
				<?php endif; ?>-->
			
				</div>
		
				<div class="card-body">
                <table class="table table-hover table-bordered table-sm display nowrap" style="width:100%" id="list">
                
                  <!--  <meta http-equiv="refresh" content="30"> -->

                    <?php if($_SESSION['login_type'] == 1): ?>
                        <colgroup>
                            <col width="10%">
                            <col width="25%">
                            <col width="35%">
                            <col width="20%">
                            <col width="10%">
                        </colgroup>
                    <?php else: ?>
                        <colgroup>
                            <col width="10%">
                            <col width="30%">
                            <col width="50%">
                            <col width="10%">
                        </colgroup>
			    <?php endif; ?>

				<thead>
					
				<th>Action &nbsp;</th>
						<?php if($_SESSION['login_type'] == 1 ): ?>		
							<th class="clerk-id">User &nbsp;</th>
							<th><input type="checkbox" id="multi-delete-checkbox"></th>
						<?php endif; ?>
						
						<th>Control No. &nbsp;</th>
						<th>Last Name &nbsp;</th>
						<th>First Name &nbsp;</th>
						<th>Middle Name &nbsp;</th>
						<th>Full Name &nbsp;</th>
						<th>Course &nbsp;</th>
						<th>Year Entry &nbsp;</th>
						<th>Year Departure &nbsp;</th>
						<th>Status  &nbsp;</th>
					</tr>
					
				</thead>
				<tbody>
                        <?php
                        $i = 1;
                        $where = '';
                        if($_SESSION['login_type'] == 1):
                            $user = $conn->query("SELECT * FROM users where id in (SELECT clerk_id FROM record) ");
                            while($row = $user->fetch_assoc()){
                                $uname[$row['id']] = ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
                            }
                        else:
                            $where = " WHERE clerk_id = '{$_SESSION['login_id']}' ";
                        endif;$qry = $conn->query("SELECT * FROM record WHERE folder_id = $folder_id AND record_status = 'notdeleted' ORDER BY UNIX_TIMESTAMP(date_created) DESC");
                        while($row = $qry->fetch_assoc()):
                            $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                            unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                        ?>
                        <tr>
                          
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="./index.php?page=folder_update_record&folder_id=<?php echo $row['folder_id'] ?>" class="btn btn-primary btn-flat">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./index.php?page=view_files&student_no=<?php echo $row['id'] ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id ?>&folder_name=<?php echo $folder_name ?>" class="btn btn-info btn-flat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($_SESSION['login_type'] == 1): ?>
                                        <button type="button" class="btn btn-danger btn-flat trash_record" data-id="<?php echo $row['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <?php if ($_SESSION['login_type'] == 1): ?>
                                <td class="clerk-id">
    <?php
    $clerkId = $row['clerk_id'];
    if ($clerkId === '1') {
        echo 'Admin 1';
    } else if ($clerkId === '2') {
        echo 'Admin 2';
    } else if ($clerkId >= '3') {
        echo 'Clerk '; echo ucwords( $clerkId);
    } else {
        echo ucwords($clerkId);
    }
    ?>
</td>
                            <?php endif; ?>
                             <?php if ($_SESSION['login_type'] == 1): ?>
                                <td>
                                    <input type="checkbox" class="record-checkbox" value="<?php echo $row['id'] ?>">
                                </td>
                            <?php endif; ?>
                            <td class="last-name"><?php echo ucwords($row['id']) ?></td>
                            
                            <td class="last-name"><?php echo ucwords($row['last_name']) ?></td>
                            <td class="first-name"><?php echo ucwords($row['first_name']) ?></td>
                            <td class="middle-name"><?php echo ucwords($row['middle_name']) ?></td>
                            <td class="full-name">
                                <?php echo ucwords($row['last_name']) ?>, <?php echo ucwords($row['first_name']) ?>, <?php echo ucwords($row['middle_name']) ?>
                            </td>
                            <td class="course-name"><?php echo ucwords($row['course_name']) ?></td>
                            <td class="year-entry"><?php echo ucwords($row['year_entry']) ?></td>
                            <td class="year-graduate"><?php echo ucwords($row['year_graduate']) ?></td>
                            <td class="grad-hd"><?php echo ucwords($row['grad_hd']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    var table = $('#list').DataTable({
        scrollX: true,
    });

    $('#filter-select').on('change', function() {
        var value = $(this).val();

        if (value === '' || value === '0') {
            // If no value is selected or "0" is selected, show all rows
            table.search('').columns().search('').draw();
        } else {
            // Filter the table based on the selected value
            table.column(1).search(value).draw();
        }
    });


        // Handle multi-delete checkbox
        $("#multi-delete-checkbox").click(function(){
            $(".record-checkbox").prop("checked", $(this).prop("checked"));
        });

        // Handle multi-delete form submission
        $(".multi-delete-btn").click(function(){
            var selectedRecordIds = [];
            $(".record-checkbox:checked").each(function(){
                var values = $(this).val().split('|');
                selectedRecordIds.push(values[0]);
                console.log(values[0]); // ID value
            });

            if(selectedRecordIds.length > 0){
                if(confirm("Are you sure you want to delete the selected records?")){
                    delete_multiple_records (selectedRecordIds);
                }
            }else{
                alert("Please select at least one record to delete.");
            }
        });


        // Handle individual record deletion
        $('#list').on('click', '.trash_record', function() {
			var record_id = $(this).data('id');
			console.log(record_id); // Log the parameter values

			_conf("Are you sure to delete this record? <br><br><small> Linked files will also be deleted</small>", "trash_record", [record_id]);
		});
        
        // Function to delete multiple records using AJAX
        function delete_multiple_records(recordIds) {
            $.ajax({
                url: "ajax.php?action=trash_multiple_records",
                method: "POST",
                data: {
                    delete: true,
                    record_ids: recordIds,
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

    function trash_record($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=trash_record',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Record Successfully Deleted",'success')
					setTimeout(function(){
						location.reload()
					},500)

				}
			}
		})
	}
</script>
