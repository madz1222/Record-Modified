<?php
if(isset($_POST['delete']) && isset($_POST['record_ids'])) {
    $selectedRecordIds = $_POST['record_ids'];

    // Replace the database connection details with your own
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "record_db";

    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process the selected record IDs
    foreach($selectedRecordIds as $recordId) {
        // Perform the deletion operation for each record
        $sql = "DELETE FROM record WHERE id = '$recordId'";
        $result = $conn->query($sql);

        // Check if the deletion was successful
        if ($result === TRUE) {
            echo "Record with ID $recordId deleted successfully.<br>";
        } else {
            echo "Error deleting record with ID $recordId: " . $conn->error . "<br>";
        }
    }

    // Close the database connection
    $conn->close();
}
?>


