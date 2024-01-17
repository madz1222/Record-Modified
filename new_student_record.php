<style>
.card-body{background: #FFE9A2;}
</style>
<?php 
if(!isset($_GET['folder_id'])) {
	$folder_id = 1;
} else {
	$folder_id = $_GET['folder_id'];
}

echo '<script>console.log("' . $folder_id . '");</script>';

if(!isset($_GET['folder_id'])) {
	echo '<script>console.log("no folder id");</script>';
}
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="new_student_record">
			<input type="hidden" name="folder_id" value="<?php echo $folder_id?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="first_name" class="form-control form-control-sm" required value="<?php echo isset($first_name) ? $first_name : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name</label>
							<input type="text" name="middle_name" class="form-control form-control-sm"  value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="last_name" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group">
  <label for="course">Course</label>
  <select name="course_name" class="form-control" required>
    <option disabled selected>Select course</option>
    <?php
      $courses = array(
        "BACHELOR OF SCIENCE IN PSYCHOLOGY",
        "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY",
        "BACHELOR OF SCIENCE IN MATHEMATICS MINOR IN COMPUTER SCIENCE",
        "BACHELOR OF SCIENCE IN BUSINESS ADMINSTRATION MAJOR IN HUMAN RESOURCE DEVELOPMENT MANAGEMENT",
        "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN DATA SCIENCE",
        "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY MAJOR IN CYBERSECURITY",
        "BACHELOR OF SCIENCE IN ENTREPRENEURSHIP",
        "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT",
        "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN RECREATION & LEISURE",
        "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN HERITAGE & CULTURE",
        "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT WITH SPECIALIZATION IN TRAVEL OPERATIONS",
        "BACHELOR OF ARTS IN COMMUNICATION",
        "BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ECONOMICS",
        "BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN ACCOUNTANCY",
        "BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM",
        "BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN COMPUTER TECHNOLOGY",
        "BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRONICS TECHNOLOGY",
        "BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN ELECTRICAL TECHNOLOGY",
        "BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION MAJOR IN AUTOMOTIVE TECHNOLOGY",
        "BACHELOR IN SECONDARY EDUCATION MAJOR IN SOCIAL STUDIES",
        "BACHELOR IN SECONDARY EDUCATION MAJOR IN SCHOOL PHYSICAL EDUCATION",
        "BACHELOR IN SECONDARY EDUCATION MAJOR IN GENERAL SCIENCE",
        "BACHELOR IN SECONDARY EDUCATION MAJOR IN ENGLISH",
        "BACHELOR IN SECONDARY EDUCATION MAJOR IN MATHEMATICS",
        "BACHELOR OF SCIENCE IN ELECTRONICS ENGINEERING",
        "BACHELOR OF SCIENCE IN COMPUTER ENGINEERING",
        "BACHELOR OF SCIENCE IN CRIMINOLOGY",
        "BACHELOR OF SCIENCE IN PHYSICAL THERAPY",
        "BACHELOR OF SCIENCE IN NURSING",
        "BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN MARKETING MANAGEMENT",
        "BACHELOR OF ARTS IN POLITICAL SCIENCE",
        "BACHELOR OF SCIENCE IN SOCIAL WORK",
        "DOCTOR OF PHILOSOPHY ON EDUCATION (PhDEd) MAJOR IN EDUCATIONAL LEADERSHIP AND MANAGEMENT",
        "DOCTOR OF PHILOSOPHY IN CRIMINAL JUSTICE (PhDCJ) WITH SPECIALIZATION IN CRIMINOLOGY",
        "MASTER IN BUSINESS ADMINISTRATION (MPA) (THESIS PROGRAM)",
        "MASTER IN BUSINESS ADMINISTRATION (MPA) (NON - THESIS PROGRAM)",
        "MASTER IN BUSINESS ADMINISTRATION (MBA) (THESIS PROGRAM)",
        "MASTER IN BUSINESS ADMINISTRATION (MBA) (NON - THESIS PROGRAM)",
        "MASTER OF ARTS IN EDUCATION MAJOR IN EDUCATIONAL LEADERSHIP AND MANAGEMENT (THESIS PROGRAM)",
        "MASTER OF ARTS IN EDUCATION MAJOR IN EDUCATIONAL LEADERSHIP AND MANAGEMENT (NON - THESIS PROGRAM)",
        "DOCTOR OF PUBLIC ADMINISTRATION (DPA)",
        "MASTER OF SCIENCE IN CRIMINAL JUSTICE WITH SPECIALIZATION IN CRIMINOLOGY (THESIS PROGRAM)",
        "MASTER OF SCIENCE IN CRIMINAL JUSTICE WITH SPECIALIZATION IN CRIMINOLOGY (NON - THESIS PROGRAM)",
		"Other"
      );

	
    $selectedCourse = isset($_POST['course_name']) ? $_POST['course_name'] : "";

    foreach ($courses as $course) {
      $isSelected = ($selectedCourse == $course) ? "selected" : "";
      echo "<option value='$course' $isSelected>$course</option>";
    }
    
	?>
  </select>
  </div>
  <div class="form-group" id="other_course_input" style="display: none;">
  <label for="other_course">Other Course</label>
  <input type="text" name="other_course" class="form-control" placeholder="Enter your course" value="<?php echo isset($_POST['other_course']) ? $_POST['other_course'] : '' ?>">
</div>

<div class="form-group">
							<label for="inputGroupFile01">Select File</label>
							<span class="text-danger"><small> (allowed file type: 'pdf','doc','ppt','txt','zip' | allowed maximum size: 30 mb ) </small></span>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
								</div>
								<div class="custom-file">
									<input type="file" name="files[]" class="custom-file-input" id="inputGroupFile01"
									aria-describedby="inputGroupFileAddon01" multiple>
									<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Year Entry</label><br>
							<label>From:</label>
							<input type="text" name="year_entry" class="form-control" placeholder="Enter Year" pattern="[0-9]{4}" title="Please enter a 4-digit number" value="<?php if(isset($_POST['upload'])) { echo $year_graduate; } ?>" required="">
						</div>
						
						<div class="form-group">
							<label>To:</label>
							<input type="text" name="year_graduate" class="form-control" placeholder="Enter Year"  value="<?php if(isset($_POST['upload'])) { echo $year_end; } ?>" required="">
						</div>
						<div class="form-group">
							<label>Status</label>
							<select name="grad_hd" class="form-control"   value = "<?php if(isset($_POST['upload'])) {
								echo $grad_hd; } ?>" required="">>
								<option disabled selected>Select Status</option>
								<option value="Graduated">Graduated</option>
								<!-- <option value="Graduated|Not Honorable Dismissed">Graduated | Not Honorable Dismissed</option> -->
								<option value="UnderGraduate">UnderGraduate</option>
								<!--<option value="UnderGraduate|Not Honorable Dismissed">UnderGraduate | Not Honorable Dismissed</option>-->
						
								</select>
						</div>
						<?php if($_SESSION['login_type'] == 1): ?>
							<div class="form-group">
    <label>User Id</label>
    <select name="clerk_id" class="form-control" required="">
        <option disabled selected>Select User</option>
        <?php
        $clerkIds = array(); // Initialize an empty array
        $user = $conn->query("SELECT * FROM users WHERE id");
        while($row = $user->fetch_assoc()){
            $clerkIds[] = $row['id']; // Add each clerk ID to the array
            $clerkName = "Clerk " . $row['id'];
            $value = $row['id'];
            if ($value === '1') {
                $clerkName = 'Admin';
            } else if ($value === '2') {
                $clerkName = 'Admin 2';
            }
            echo '<option value="' . $value . '">' . $clerkName . '</option>';
        }
        ?>
    </select>    
</div>

						<?php endif; ?>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-success mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=record_list'">Cancel</button>
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
	$('#new_student_record').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		var form = $(this);
		var formData = new FormData(form[0]);
		var files = $('#inputGroupFile01')[0].files;	

		$.ajax({
			url:'ajax.php?action=new_student',
     		data: formData,
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Record Successfully Uploaded',"success");
					setTimeout(function(){
						location.href = 'index.php?page=record_list'
					},2000)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>&nbsp Detected duplicate entry for this record.</div>");
					$('[name="first_name"], [name="middle_name"], [name="last_name"]').addClass("border-danger");
					end_load();	
					alert_toast('Detected duplicate entry for this record',"warning");
			}
			},
			
		})
	})
	var courseSelect = document.querySelector('select[name="course_name"]');
  var otherCourseInput = document.getElementById('other_course_input');
  var courseNameInput = document.querySelector('input[name="course_name"]');
  var otherCourseValue = document.querySelector('input[name="other_course"]');

  courseSelect.addEventListener('change', function() {
    var selectedOption = this.value;

    if (selectedOption === "Other") {
      otherCourseInput.style.display = 'block';
      courseNameInput.value = otherCourseValue.value;
    } else {
      otherCourseInput.style.display = 'none';
      courseNameInput.value = selectedOption;
    }
  });
</script>