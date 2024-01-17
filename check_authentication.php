<?php
// index.php

session_start();

if (isset($_SESSION['user_id'])) {
    // User is logged in
    $fileId = $_GET['id']; // Get the file ID from the URL parameter

    $allowedFiles = array(
        'file1' => 'path_to_file1',
        'file2' => 'path_to_file2',
        'file3' => 'path_to_file3'
    );

    if (isset($allowedFiles[$fileId])) {
        // File ID is valid, check authorization if required

        // Example authorization check:
        // Perform additional authorization checks based on your application's logic
        // You can check user permissions or any other criteria

        // If authorized, serve the file
        $file = $allowedFiles[$fileId];

        // Check if the user is still logged in
        $loggedIn = true;
        if (!isset($_SESSION['user_id'])) {
            $loggedIn = false;
        }

        if ($loggedIn) {
            // Set appropriate headers to prevent caching and disable direct access
            header('Cache-Control: private');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            readfile($file);
            exit;
        }
    }
}

// User is not logged in or file ID is invalid
// Redirect to login page or display an error message
header('Location: login.php');
exit;
?>
