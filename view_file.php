<?php
// Retrieve the file and student_no from the query parameters
$file = $_GET['file'];
$student_no = $_GET['student_no'];
$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : "none";
$val = isset($_GET['val']) ? $_GET['val'] : "none";

// Construct the file path
$filePath = 'userfiles/' . $student_no . '/' . $file;

// Check if the user is logged in as type 1 (login type for success) or type 2 (login type for clerk)
if ($_SESSION['login_type'] != 1 && $_SESSION['login_type'] != 2 && $_SESSION['login_type'] != 3) {
    // Echo a JavaScript redirect script
    echo "<script>window.location.href = 'index.php?page=record_list';</script>";
    exit();
}

?>
<html>
<head>
  <title>View File</title>
  <style>
    .container {
      display: flex;
      align-items: center;
      flex-direction: column;
    }
    .back-link {
    /* Add custom CSS rules here */
    padding: 10px 20px;
    font-size: 16px;
    margin: 15px;
    width: 20%;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="./index.php?page=view_files&student_no=<?php echo $student_no ?>&val=<?php echo $val ?>&folder_id=<?php echo $folder_id ?>" class="btn btn-secondary back-link">
      <i class="fas fa-arrow-left"></i>
      Back
    </a>

    <embed src="<?php echo $filePath; ?>" width="90%" height="600px" type="application/pdf" />
  </div>
</body>
</html>
