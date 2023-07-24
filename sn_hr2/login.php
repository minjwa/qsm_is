<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $role = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password, role_id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            $sql_role = "SELECT * FROM roles WHERE id = ". $role ." LIMIT 1";
                            $result_role = $link->query($sql_role);
                            $role_user = $result_role->fetch_assoc();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION['role'] = $role_user;

                            // Redirect user to welcome page
                            header("location: home.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 15px sans-serif;
            background:url('img/bg.jpg')no-repeat;
            background-size:cover;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }

        footer {
            text-align: center;
            padding: 3px;
            color: white;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php
            if (!empty($login_err)) {
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="col-12 mt-5" method="post">
                 <div class="d-flex" style="border:0px solid blue;">
                 <div class="card" style="width:400px;border-left:5px solid #dda314;border-right:5px solid #989896;border-bottom:5px solid #989896;background-color:#fff;border-radius:20px;margin-left:100px;margin-top:5%;">
                 <div class="card-body" style="border:0px solid red;">
                 <div class="d-flex justify-content-center" >
                     <img src="img/logo.png" alt="hr2eazy_logo" class="mx-auto d-block" style="width:60%;">
                 </div>

                 <div class="form-group row mt-5">
                     <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Username</b></label><br>
                 <div style="border:0px solid red;width:85%;margin:auto;">
                     <input style="border-radius: 25px;box-shadow:5px 5px 0px 0px #384182;" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                     <span class="invalid-feedback"><?php echo $username_err; ?></span>
                 </div>
                 </div>

                 <div class="form-group row">
                     <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Password</b></label>
                 <div style="border:0px solid red;width:85%;margin:auto;">
                     <input style="border-radius: 25px;box-shadow:5px 5px 0px 0px #384182;" type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                     <span class="invalid-feedback"><?php echo $password_err; ?></span>
                 </div>
                 </div>
                            
                 <div class="form-group row mt-4">
                 <div style="margin:auto;width:85%;">
                     <input type="submit" class="btn" style="color: #fff;background-color: #dda314;border-radius: 25px;width:100%;margin:auto;" value="Login">
                 </div>
                 </div>
                          
                 <p style="text-align:center;">First Time User? <a href="register.php" style="color:#422B69;"><b>Sign up</b></a>.</p>
                 </div>
                 </div>
                 </div>
            </form>
        </div>
    </div>
    
</body>

</html>
