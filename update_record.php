<style>
.card-body{background: #FFE9A2;}
</style>

<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM record where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$val = isset($_GET['val']) ? $_GET['val'] : "none";
$course = isset($_GET['course']) ? $_GET['course'] : "none";
$year = isset($_GET['year']) ? $_GET['year'] : "none";
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
						<select name="course_name" class="form-control" required="">
							<option value="">Select Course</option>
							<option value="BACHELOR OF SCIENCE IN PSYCHOLOGY"<?php echo $course_name == 'BACHELOR OF SCIENCE IN PSYCHOLOGY' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN PSYCHOLOGY</option>
						<option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY"<?php echo $course_name == 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</option>
						<option value="BACHELOR OF SCIENCE IN BUSINESS ADMINSTRATION MAJOR IN HUMAN RESOURCE DEVELOPMENT MANAGEMENT"<?php echo $course_name == 'BACHELOR OF SCIENCE IN BUSINESS ADMINSTRATION MAJOR IN HUMAN RESOURCE DEVELOPMENT MANAGEMENT' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN HUMAN RESOURCE DEVELOPMENT MANAGEMENT</option>
						<option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN DATA SCIENCE"<?php echo $course_name == 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN DATA SCIENCE' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN DATA SCIENCE</option>
						<option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN CYBERSECURITY"<?php echo $course_name == 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN CYBERSECURITY' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN CYBERSECURITY</option>
						<option value="BACHELOR OF SCIENCE IN ENTREPRENEURSHIP"<?php echo $course_name == 'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</option>
						<option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT"<?php echo $course_name == 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT</option>
						<option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN RECREATION & LEISURE"<?php echo $course_name == 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN RECREATION & LEISURE' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN RECREATION & LEISURE</option>
						<option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN HERITAGE & CULTURE"<?php echo $course_name == 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN HERITAGE & CULTURE' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN HERITAGE & CULTURE</option>
						<option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN TRAVEL OPERATIONS"<?php echo $course_name == 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN TRAVEL OPERATIONS' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN TRAVEL OPERATIONS</option>
						<option value="BACHELOR OF ARTS IN COMMUNICATION"<?php echo $course_name == 'BACHELOR OF ARTS IN COMMUNICATION' ? ' selected' : ''; ?>>BACHELOR OF ARTS IN COMMUNICATION</option>
						<option value="BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ECONOMICS"<?php echo $course_name == 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ECONOMICS' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ECONOMICS</option>
						<option value="BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ACCOUNTANCY"<?php echo $course_name == 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ACCOUNTANCY' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ACCOUNTANCY</option>
						<option value="BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM"<?php echo $course_name == 'BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM' ? ' selected' : ''; ?>>BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM</option>
						<option value="BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN COMPUTER TECHNOLOGY"<?php echo $course_name == 'BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN COMPUTER TECHNOLOGY' ? ' selected' : ''; ?>>BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN COMPUTER TECHNOLOGY</option>
						<option value="BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRONICS TECHNOLOGY"<?php echo $course_name == 'BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRONICS TECHNOLOGY' ? ' selected' : ''; ?>>BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRONICS TECHNOLOGY</option>
						<option value="BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRICAL TECHNOLOGY"<?php echo $course_name == 'BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRICAL TECHNOLOGY' ? ' selected' : ''; ?>>BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRICAL TECHNOLOGY</option>
						<option value="BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN AUTOMOTIVE TECHNOLOGY"<?php echo $course_name == 'BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN AUTOMOTIVE TECHNOLOGY' ? ' selected' : ''; ?>>BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN AUTOMOTIVE TECHNOLOGY</option>
						<option value="BACHELOR IN SECONDARY EDUCATION MAJOR IN SOCIAL STUDIES"<?php echo $course_name == 'BACHELOR IN SECONDARY EDUCATION MAJOR IN SOCIAL STUDIES' ? ' selected' : ''; ?>>BACHELOR IN SECONDARY EDUCATION MAJOR IN SOCIAL STUDIES</option>

						<!-- Add more options here -->
							<option value="Other" <?php echo $course_name == 'Other' ? 'selected' : ''; ?>>Other</option>
						</select>
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
						<?php if($_SESSION['login_type'] == 3): ?>
							<input type="hidden" name="clerk_id" value="<?php echo isset($clerk_id) ? $clerk_id : '' ?>">
						<?php endif; ?>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2" name="update">Update</button>
					<?php if($val == "record_list"):?>
						<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=record_list'">Cancel</button>
					<?php endif; ?>
					<?php if($val == "year"):?>
						<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=year&course=<?php echo $course ?>&year=<?php echo $year?>'">Cancel</button>
					<?php endif; ?>
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
	console.log('<?php echo $val?>');
	$('#update_record').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		
		$.ajax({
			url: 'ajax.php?action=update_record',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', "success");
					setTimeout(function() {
							window.location.replace('<?php if ($val == "record_list") : ?>
														index.php?page=record_list<?php endif;?>
													<?php if ($val == "year") : ?>
														index.php?page=year&course=<?php echo $course ?>&year=<?php echo $year?><?php endif;?>
													');
					}, 1500);
				} else if (resp == 2) {
					// Handle response code 2
				}
			}
		});
	})
</script>

