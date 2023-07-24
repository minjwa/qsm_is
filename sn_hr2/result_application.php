<?php
require 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted values
    if (isset($_POST['type_application']) && isset($_POST['application_id'])) {
        $typeApplication = $_POST['type_application'];
        $applicationId = $_POST['application_id'];

        // Perform the database update based on the selected action
        if ($typeApplication == '1') {
            // Update the status as '1' (accept) in the database for the specified application ID
            $sql = "UPDATE applications SET status = '1' WHERE id = '$applicationId'";
            $result = $link->query($sql);

            if ($result) {
                // Update successful
                // Perform any additional actions or redirect as needed
                // ...
            } else {
                // Update failed
                // Handle the error or display a message to the user
                // ...
            }
        } elseif ($typeApplication == '2') {
            // Update the status as '2' (reject) in the database for the specified application ID
            $sql = "UPDATE applications SET status = '2' WHERE id = '$applicationId'";
            $result = $link->query($sql);

            if ($result) {
                // Update successful
                // Perform any additional actions or redirect as needed
                // ...
            } else {
                // Update failed
                // Handle the error or display a message to the user
                // ...
            }
        }
    }
}

header("location: home.php");
exit;
?>
