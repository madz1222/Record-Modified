<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if any files are selected
    if (isset($_POST['selected_files']) && is_array($_POST['selected_files']) && count($_POST['selected_files']) > 0) {
        // Prepare the command for Ghostscript
        $outputFile = 'merged.pdf';
        $command = 'gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=' . $outputFile;

        // Add the selected files to the Ghostscript command
        foreach ($_POST['selected_files'] as $file) {
            // Validate the file path to prevent any security issues
            $file = escapeshellarg($file);
            $command .= ' ' . $file;
        }

        // Execute the Ghostscript command
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            // Merging successful, you can redirect or provide a download link for the merged file
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="merged.pdf"');
            readfile($outputFile);
            exit;
        } else {
            // Merging failed, handle the error
            echo 'An error occurred while merging the PDF files.';
            // Output the Ghostscript command, output, and error message for troubleshooting
            echo '<pre>';
            echo 'Command: ' . $command . PHP_EOL;
            echo 'Output: ' . implode(PHP_EOL, $output) . PHP_EOL;
            echo 'Error: ' . $returnCode . PHP_EOL;
            echo '</pre>';
            exit;
        }
    } else {
        echo 'No files selected for merging.';
        exit;
    }
} else {
    echo 'Invalid request.';
    exit;
}
?>
