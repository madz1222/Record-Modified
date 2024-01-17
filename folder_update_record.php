<style>
.card-body{background: #FFE9A2;}
</style>

<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM record where folder_id = ".$_GET['folder_id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
 
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="update_record">
			<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="first_name" class="form-control form-control-sm" required value="<?php echo isset($first_name) ? $first_name : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name</label>
							<input type="text" name="middle_name" class="form-control form-control-sm"  value="<?php echo isset($middle_name) ? $middle_name : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="last_name" class="form-control form-control-sm" required value="<?php echo isset($last_name) ? $last_name : '' ?>">
						</div>
						<div class="form-group">
							<label>Course</label>
							<input type="text" name="course_name" class="form-control" placeholder=""  value = "<?php echo isset($course_name) ? $course_name : '' ?>" required="">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Year Entry</label><br>
							<label>From:</label>
							<input type="text" name="year_entry" class="form-control" placeholder="Enter Year" pattern="[0-9]{4}" title="Please enter a 4-digit number" 
							value = "<?php echo isset($year_entry) ? $year_entry : '' ?>" required="">
						</div>
						
						<div class="form-group">
							<label>To:</label>
							<input type="text" name="year_graduate" class="form-control" placeholder="Enter Year" 
							value = "<?php echo isset($year_graduate) ? $year_graduate : '' ?>" required="">
						</div>
						<div class="form-group">
							<label>Status</label>
							<select name="grad_hd" class="form-control"   value = "<?php echo isset($grad_hd) ? $grad_hd : '' ?>" required="">>
							<option value="Graduated">Graduated</option>
								<!-- <option value="Graduated|Not Honorable Dismissed">Graduated | Not Honorable Dismissed</option> -->
								<option value="UnderGraduate">UnderGraduate</option>
								<!--<option value="UnderGraduate|Not Honorable Dismissed">UnderGraduate | Not Honorable Dismissed</option>-->
							</select> 
						</div>
						<?php if($_SESSION['login_type'] == 1): ?>
						<div class="form-group">
						
							<label>Clerk Id</label>
							<select name="clerk_id" class="form-control" value = "<?php echo isset($clerk_id) ? $clerk_id : '' ?>" required="">>
							<option value="<?php echo isset($clerk_id) ? $clerk_id : '' ?>"><?php echo isset($clerk_id) ? $clerk_id : '' ?></option>
									<?php
									$clerkIds = array(); // Initialize an empty array
									$user = $conn->query("SELECT * FROM users WHERE id");
									while($row = $user->fetch_assoc()){
										$clerkIds[] = $row['id']; // Add each clerk ID to the array
										$clerkName = "Clerk " . $row['id'];
										echo '<option value="' . $row['id'] . '">' . $clerkName . '</option>';
									}
									?>
							</select>	
						</div>
						<?php endif;?>
						<?php if($_SESSION['login_type'] == 2): ?>
							<input type="hidden" name="clerk_id" value="<?php echo isset($clerk_id) ? $clerk_id : '' ?>">
						<?php endif; ?>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2" name="update">Update</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=folder&folder_id=<?php echo $folder_id?>'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	img#cimg{
		max-height: 15vh;
		/*max-width: 6vw;*/
	}
</style>
<script>
	$('#update_record').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		
		$.ajax({
			url:'ajax.php?action=update_record',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=folder&folder_id=<?php echo $folder_id?>')
					},1500)
				}else if(resp == 2){
				}
			}
		})
	})
</script>
<?php