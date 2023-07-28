<?php
require 'config.php';

$target_dir = "uploads/manual/";
$target_file = $target_dir . basename($_FILES["document"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST['user_id'];

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["document"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    // if (
    //     $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    //     && $imageFileType != "gif"
    // ) {
    //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    //     $uploadOk = 0;
    // }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["document"]["name"])) . " has been uploaded.";

            // $sql_document = "UPDATE users SET document='" . $target_file . "' WHERE id=" . $user_id;

            // if ($link->query($sql_document) === TRUE) {
            //     // echo "existing record update successfully";
            //     echo "existing record insert successfully";
            // } else {
            //     echo "Error: " . $sql_document . "<br>" . $link->error;
            // }

            // $link->close();

            

            // $sql = "INSERT INTO document (id, user_id, name) VALUES (NULL, ?, ?)";

            // if($statement = mysqli_prepare($link, $sql)){
            //     mysqli_stmt_bind_param($statement, "ss", $user_id, $target_file);

            //     if(mysqli_stmt_execute($statement)){
            //         echo "existing record insert successfully";
            //     }else{
            //         echo "Error insert document data";
            //     }
            // }

            // mysqli_stmt_close($statement);

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

header("location: home.php");

exit;
?>
