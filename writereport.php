

<?php
include 'db_connect.php';

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
?>
<html>
<head>
    <title>Document Report</title>
</head>
<body>
<form method="post" action="generate_certificate.php?student_no=<?php echo $student_no; ?>" target="_blank">
 
 
    <br>
    <label for="date">Date:</label>
    <input type="date" name="date" id="date">
    <br>
    <br>
    <b>REQUESTED DOCUMENTS    </b>
    <hr class="border-success">

    <center> <b class="text-muted">Please check (✔️) the following.. </b> </center>
    <br>
    <div class="card-body">
    <label for="tor">
        <input type="checkbox" name="documents[]" id="tor" value="TOR"> TOR
    </label>
    <br>
    <label for="form137">
        <input type="checkbox" name="documents[]" id="form137" value="Form 137"> Form 137
    </label>
    <br>
    <label for="birthCert">
        <input type="checkbox" name="documents[]" id="birthCert" value="Birth Certificate"> Birth Certificate
    </label>
    <br>
    <label for="diploma">
        <input type="checkbox" name="documents[]" id="diploma" value="Diploma"> Diploma
    </label>
    <br>
    <label for="certificate">
        <input type="checkbox" name="documents[]" id="certificate" value="Certificate"> Certificate
    </label>
</div>

    <br>
     Other:
   
    
    <br>

    <div id="additional-documents"></div>
    <a class="btn btn-primary" onclick="addDocumentField()"><i class="fa fa-plus"></a></i>
    <br>

   
</form>

<script>
    function addDocumentField() {
        var div = document.createElement("div");
        var input = document.createElement("input");
        input.type = "text";
        input.name = "additional_documents[]";
        input.placeholder = "Enter additional document name";
        div.appendChild(input);
        document.getElementById("additional-documents").appendChild(div);
    }
</script>

</body>
</html>
