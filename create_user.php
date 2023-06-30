<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $role_id = trim($_POST['role_id']);
    $password = password_hash("1234", PASSWORD_DEFAULT);

    $sql_user = "INSERT INTO users (email, username, role_id, password) VALUES ('" . $email . "', '" . $username . "', '" . $role_id . "', '" . $password . "')";

    if ($link->query($sql_user) === TRUE) {
        echo "existing record update successfully";
    } else {
        echo "Error: " . $sql_user . "<br>" . $link->error;
    }

    $link->close();

}

header("location: users_page.php");
exit;
?>