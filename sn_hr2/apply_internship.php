<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $job_id = trim($_POST['job_id']);
    $user_id = trim($_POST['user_id']);
    $status = trim($_POST['status']);

    $sql_job = "INSERT INTO applications (job_id, user_id, status) VALUES ('" . $job_id . "', '" . $user_id . "', '" . $status . "')";

    if ($link->query($sql_job) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql_job . "<br>" . $link->error;
    }

    $link->close();

}

header("location: booking_training.php");
exit;
?>