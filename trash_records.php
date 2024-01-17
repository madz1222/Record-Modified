<style>
    .table-bordered{background: #f2f2f2;}
    .btnADD{background: gold; border: 1px solid black;}
    .btn{border: 1px solid black;}
    .bg-bgcustom {
		background-color: rgb(150,150,150);
	}
</style>

<form method="POST" action="" id="multi-delete-form">
    <div class="col-sm-12">
        <div class="card card-outline">
            <div class="card-header bg-bgcustom">	    
                <div class="card-tools ml-">
                    <a class="btnBACK btn-block btn-sm btn-secondary btn-flat" href="./index.php"><i class="fas fa-arrow-left"></i> Back </a>
                </div>
				<?php if($_SESSION['login_type'] == 1): ?>
					<div class="card-tools mr-3">
						<a class="btn btn-block btn-sm btn-danger btn-flat multi-delete-btn"><i class="fas fa-trash"></i> Delete Selected</a>
					</div>
                    <div class="card-tools mr-3">
						<a class="btn btn-block btn-sm btn-success btn-flat multi-restore-btn"><i class="fas fa-trash-restore"></i> Restore Selected</a>
					</div>  
                    <div>
                <b style='color:white' >Trash Records</b>
                
                </div>
				<?php endif; ?>

			<!--	<?php if($_SESSION['login_type'] == 1): ?>
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
                
                    <meta http-equiv="refresh" content="30">

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
                    <tr>
                    <th>Days&nbsp;(30)&nbsp;</th>
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
                        endif;
                        $qry = $conn->query("SELECT * FROM record WHERE record_status = 'deleted' $where ORDER BY UNIX_TIMESTAMP(date_created) DESC ");
                        while($row = $qry->fetch_assoc()):
                            $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                            unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                        ?>
                        <tr>
                        <td class="days-deleted">
    <?php
        $dateCreated = new DateTime($row['date_created']);
        $currentDate = new DateTime();
        $daysDeleted = $currentDate->diff($dateCreated)->days;
        echo $daysDeleted;
    ?>
</td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-flat restore_record" data-id="<?php echo $row['id']?>">
                                        <i class="fas fa-trash-restore"></i>
                                    </button>
                                    <a href="./index.php?page=view_files&student_no=<?php echo $row['id'] ?>&val=trash_record" class="btn btn-info btn-flat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($_SESSION['login_type'] == 1): ?>
                                        <button type="button" class="btn btn-danger btn-flat delete_record" data-id="<?php echo $row['id']?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <?php if ($_SESSION['login_type'] == 1): ?>
                                <td class="clerk-id">Clerk <?php echo ucwords($row['clerk_id']) ?></td>
                            <?php endif; ?>
                            <?php if ($_SESSION['login_type'] == 1): ?>
                                <td>
                                    <input type="checkbox" class="record-checkbox" value="<?php echo $row['id']?>">
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
<!-- AUTO DELETE AFTER 30 DAYS QUERY -->
<?php
$deleteQuery = "DELETE FROM record WHERE record_status = 'deleted' AND date_created <= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$conn->query($deleteQuery);
?>
<!-- AUTO DELETE AFTER 30 DAYS QUERY -->
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
            var selected_record_ids = [];
            $(".record-checkbox:checked").each(function(){
                var values = $(this).val().split('|');
                selected_record_ids.push(values[0]);
                console.log(values[0]); // ID value
            });

            if(selected_record_ids.length > 0){
                if(confirm("Are you sure you want to delete the selected records?")){
                    delete_multiple_records (selected_record_ids);
                }
            }else{
                alert("Please select at least one record to delete.");
            }
        });

        // Handle multi-restore form submission
        $(".multi-restore-btn").click(function(){
            var selected_record_ids = [];
            $(".record-checkbox:checked").each(function(){
                var values = $(this).val().split('|');
                selected_record_ids.push(values[0]);
                console.log(values[0]); // ID value
            });

            if(selected_record_ids.length > 0){
                if(confirm("Are you sure you want to restore the selected records?")){
                    restore_multiple_records (selected_record_ids);
                }
            }else{
                alert("Please select at least one record to restore.");
            }
        });


        // Handle individual record deletion
        $('#list').on('click', '.delete_record', function() {
			var record_id = $(this).data('id');
			console.log(record_id); // Log the parameter values

			_conf("Are you sure to delete this record? <br><br><small> Linked files will also be deleted</small>",
                  "delete_record",
                [record_id]);
		});

        $('#list').on('click', '.restore_record', function() {
			var record_id = $(this).data('id');

			_conf("Are you sure to restore this record?",
                  "restore_record",
                [record_id]);
		});
        
        // Function to delete multiple records using AJAX
        function delete_multiple_records(record_ids) {
            $.ajax({
                url: "ajax.php?action=delete_multiple_records",
                method: "POST",
                data: {
                    delete: true,
                    record_ids: record_ids,
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

        // Function to restore multiple records using AJAX
        function restore_multiple_records(record_ids) {
            $.ajax({
                url: "ajax.php?action=restore_multiple_records",
                method: "POST",
                data: {
                    record_ids: record_ids,
                },
                success: function(response) {
                    if (response == 1) {
                        alert_toast("Records Successfully Restored", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (response == 2) {
                        alert_toast("Error Restoring Records", 'danger');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    });

    function delete_record($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_record',
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

    function restore_record($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=restore_record',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Record Successfully Restored",'success')
					setTimeout(function(){
						location.reload()
					},500)
				}
			}
		})
	}
</script>
