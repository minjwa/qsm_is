<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $job_id = trim($_POST['job_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location_id = trim($_POST['location_id']);

    $sql_job = "UPDATE jobs SET name='" . $name . "', description='" . $description . "', location_id = '" . $location_id . "' WHERE id=" . $job_id;

    if ($link->query($sql_job) === TRUE) {
        echo "existing record update successfully";
    } else {
        echo "Error: " . $sql_job . "<br>" . $link->error;
    }

    $link->close();

}

header("location: internship_page.php");
exit;
?>