<style>
.card-body{background: #FFE9A2;}
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


?>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
    <center><b style='color:black'> <?php echo $student_name; ?></b></center>
    
            <form action="" id="new_file">
                <input type="hidden" name="student_no" value="<?php echo isset($student_no) ? $student_no : '' ?>">
                <br>
                <div class="row">
                    <div class="col-md-12" id="fileinputcont">
                        <div class="form-group">
                           
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
                            <label for="inputGroupFile01">Select File</label>
                            <span class="text-danger"><small> (allowed file type: 'pdf', 'doc', 'ppt', 'txt', 'zip' | allowed maximum size: 30 mb)</small></span>  
                        </div>
                    </div>
                </div>
                <?php if($_SESSION['login_type'] == 1 ): ?>
                    <?php
                    $user = $conn->query("SELECT * FROM users where id in (SELECT clerk_id FROM record) ");
                    while($row = $user->fetch_assoc()){
                        $uname[$row['id']] = ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
                    }
                    ?>
                <?php else: ?>
                    <?php $where = " where clerk_id = '{$_SESSION['login_id']}' "; ?>
                <?php endif; ?>

                
            
        </div>
    </div>
</div><div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2">Save</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = './index.php?page=view_files&student_no=<?php echo $_GET['student_no'] ?>&folder_id=<?php echo $folder_id?>&val=<?php echo $val ?>'">Cancel</button>
                </div>
            </form>
				<style>
	img#cimg{
		max-height: 15vh;
		/*max-width: 6vw;*/
	}
</style>
<script>
$('#new_file').submit(function(e) {
   e.preventDefault();
   $('input').removeClass("border-danger");
   start_load();
   $('#msg').html('');

   var form = $(this);
   var formData = new FormData(form[0]);
   var files = $('#inputGroupFile01')[0].files;

   // Append each selected file to the formData object

   $.ajax({
      url: 'ajax.php?action=new_file',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      success: function(resp) {
         if (resp == 1) {
            alert_toast('Files Uploaded', "success");
            setTimeout(function() {
               location.href = './index.php?page=view_files&student_no=<?php echo $_GET['student_no'] ?>&val=<?php echo $val ?>';
            }, 2000);
         } else {
            alert_toast('File Upload Failed', "error");
         }
      },
      error: function(xhr, status, error) {
         alert_toast('File Upload Failed', "error");
      }
   });
});

</script>