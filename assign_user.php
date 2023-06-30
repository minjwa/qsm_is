<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = trim($_POST['user_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location_id = trim($_POST['location_id']);

    $sql_job = "UPDATE users SET role_id='3' WHERE id=" . $user_id;

    if ($link->query($sql_job) === TRUE) {
        echo "existing record update successfully";
    } else {
        echo "Error: " . $sql_job . "<br>" . $link->error;
    }

    $link->close();

}

header("location: users_page.php");
exit;
?>