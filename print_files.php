<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected files to print
    $filesToPrint = $_POST['files_to_print'];

    // Perform the printing operation for each selected file
    foreach ($filesToPrint as $file) {
        // Implement your printing logic here
        // This is just a placeholder code that echoes the file path
        echo "Printing file: $file <br>";
        // Add your actual printing code here
        // Example:
        // printFile($file);
    }
} else {
    // If the form is not submitted, redirect to the view_files page
    header("Location: view_files.php?student_no=$_GET[student_no]&val=$_GET[val]&folder_id=$_GET[folder_id]");
    exit();
}

// Function to print a file (example)
function printFile($filePath) {
    // Add your printing code here
    // Example:
    // shell_exec("lp $filePath");
}
?>
