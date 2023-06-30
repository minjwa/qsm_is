<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location_id = trim($_POST['location_id']);

    $sql_job = "INSERT INTO jobs (name, description, location_id) VALUES ('" . $name . "', '" . $description . "', '" . $location_id . "')";

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