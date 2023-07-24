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
            font: 18px sans-serif;
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

    <style>

       body { 
        background: url('img/bw.jpg') no-repeat; 
        background-size: cover;
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
                 <div class="d-flex justify-content-center">
                 <div class="card col-6" style="border-style: solid; background-color: hsla(40, 42%, 62%, 0.7) ; border-color: #000000; border-radius:25px; border-width:2px;">
                 <div class="card-body">
                 <div class="d-flex justify-content-center">
                     <img src="img/hr2eazy.png" alt="hr2eazy_logo" class="mx-auto d-block" style="height: 100px;">
                 </div>

                 <div class="form-group row mt-5">
                     <label class="col-sm-2 col-form-label"><b>Username</b></label>
                 <div class="col-sm-10">
                     <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                     <span class="invalid-feedback"><?php echo $username_err; ?></span>
                 </div>
                 </div>

                 <div class="form-group row">
                     <label class="col-sm-2 col-form-label"><b>Password</b></label>
                 <div class="col-sm-10">
                     <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                     <span class="invalid-feedback"><?php echo $password_err; ?></span>
                 </div>
                 </div>
                            
                 <div class="form-group row mt-4">
                 <div class="col-sm-2"></div>
                 <div class="col-sm-10">
                     <input type="submit" class="btn px-4" style="color: #fff;background-color: #422B69; border-color: #422B69; border-radius: 28px;" value="Login">
                 </div>
                 </div>
                          
                 <p>First Time User? <a href="register.php" style="color:#422B69;"><b>Sign up</b></a>.</p>
                 </div>
                 </div>
                 </div>
            </form>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <footer>
  <p style="color: black;">©2023_hr2eazy</p>
  <p style="color: black;">Special Project_Training Booking</p><br>
     </footer>
</body>

</html>