<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $application_id = trim($_POST['application_id']);
    $result = trim($_POST['type_application']);

    $sql_job = "UPDATE applications SET result='" . $result . "' WHERE id = " . $application_id;

    if ($link->query($sql_job) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql_job . "<br>" . $link->error;
    }

    $link->close();
}

header("location: home.php");
exit;
?>