<?php
// Directory path to the "uploads/manual" folder
$folderPath = "uploads/manual/";

// Get the filename from the query parameter
if (isset($_GET['file'])) {
    $filename = $_GET['file'];

    // Make sure the filename is valid and not containing any path traversal
    $filename = basename($filename);

    // Set the appropriate headers to force the download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($folderPath . $filename));

    // Read the file and output it to the browser for download
    readfile($folderPath . $filename);

    // Terminate the script to prevent any additional output
    exit();
}
?>
