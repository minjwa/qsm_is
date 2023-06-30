<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = trim($_POST['user_id']);

    $sql_user = "DELETE FROM users WHERE id = ". $user_id;

    if ($link->query($sql_user) === TRUE) {
        echo "Record Deleted";
    } else {
        echo "Error: " . $sql_user . "<br>" . $link->error;
    }

    $link->close();
}


header("location: users_page.php");
exit;
?>