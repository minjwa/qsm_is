<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = trim($_POST['user_id']);
    $file_dir = trim($_POST['file_dir']);

    // $sql_user = "UPDATE users SET document = null WHERE id=" . $user_id;
    $sql_user = "DELETE from document WHERE user_id=" . $user_id;

    if ($link->query($sql_user) === TRUE) {
        echo "existing record update successfully";

        unlink($file_dir);
    } else {
        echo "Error: " . $sql_user . "<br>" . $link->error;
    }

    $link->close();

}

header("location: home.php");
exit;
?>