<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $job_id = trim($_POST['job_id']);

    $sql_job = "DELETE FROM jobs WHERE id = ". $job_id;

    if ($link->query($sql_job) === TRUE) {
        echo "Record Deleted";
    } else {
        echo "Error: " . $sql_job . "<br>" . $link->error;
    }

    $link->close();
}


header("location: booking_training.php");
exit;
?>