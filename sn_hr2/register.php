<?php
// Include config file
require_once "config.php";

//testing commit

// Define variables and initialize with empty values
$email = $username = $password = $confirm_password = $role = "";
$roles = [];
$email_err = $username_err = $password_err = $confirm_password_err = $role_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($link !== false) {
        $sql_roles = "SELECT * FROM roles";
        $result_roles = $link->query($sql_roles);

        while ($row = $result_roles->fetch_assoc()) {
            # code...
            $roles[] = [
                "id" => $row["id"],
                "code" => $row["code"],
                "name" => $row["name"],
            ];
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter a email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores." ;
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate Role
    if (empty(trim($_POST["role_user"]))) {
        $role_err = "Please choose role";
    } else {
        $role = trim($_POST["role_user"]);
        $role_err = "";
    }

    // Check input errors before inserting in database
    if (empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (email, username, password, role_id) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_username, $param_password, $param_role);

            // Set parameters
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_role = $role;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
} else {
    if ($link !== false) {
        $sql_roles = "SELECT * FROM roles";
        $result_roles = $link->query($sql_roles);

        while ($row = $result_roles->fetch_assoc()) {
            if($row["name"] == "Student"){
                array_push($roles, [
                    "id" => $row["id"],
                    "code" => $row["code"],
                    "name" => "User",    
                ]);
                continue;
            }
            if($row["name"] === "Company"){
                array_push($roles, [
                    "id" => $row["id"],
                    "code" => $row["code"],
                    "name" => "Admin",    
                ]);
                continue;
            }
            if($row["name"] === "Supervisor"){
                continue;
            }

            array_push($roles, [
                "id" => $row["id"],
                "code" => $row["code"],
                "name" => $row["name"],
            ]);
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-size:14px;
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
    <!-- <?php 
        // echo "<pre>";
        // print_r($roles);
        // echo "</pre>";
    ?> -->
    <div class="container-fluid">
    <div class="row">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="col-12" method="post">
            <input type="hidden" name="role_user" id="role_user">
    <div class="d-flex">
    <div class="card" style="width:400px;border-left:5px solid #dda314;border-right:5px solid #989896;border-bottom:5px solid #989896;background-color:#fff;border-radius:20px;margin-left:100px;margin-top:3%;">
    <div class="card-body">
    <div class="d-flex justify-content-center" >
                     <img src="img/logo.png" alt="hr2eazy_logo" class="mx-auto d-block" style="width:48%;">
                 </div>
                           
    <div class="form-group row mt-4">
        <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Email</b></label>
    <div style="border:0px solid red;width:85%;margin:auto;">
            <input type="text" name="email" style="border-radius: 25px;box-shadow:5px 5px 0px 0px #384182;height:100%;" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
        <span class="invalid-feedback"><?php echo $email_err; ?></span>
    </div>
    </div>

    <div class="form-group row">
        <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Username</b></label>
    <div style="border:0px solid red;width:85%;margin:auto;">
            <input type="text" name="username" style="border-radius: 25px;box-shadow:5px 5px 0px 0px #384182;height:100%;" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
    </div>
    </div>
                           
    <div class="form-group row">
        <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Password</b></label>
    <div style="border:0px solid red;width:85%;margin:auto;">
            <input type="password" name="password" style="border-radius: 25px;box-shadow:5px 5px 0px 0px #384182;height:100%;" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
         <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    </div>
                            
    <div class="form-group row">
        <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Repeat Password:</b></label>
    <div style="border:0px solid red;width:85%;margin:auto;">
            <input type="password" name="confirm_password" style="border-radius: 25px;box-shadow:5px 5px 0px 0px #384182;height:100%;" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
     </div>
     </div>
                           
    <div class="row">
        <label class="form-label" style="border:0px solid blue;width:85%;margin:auto;margin-bottom:10px;"><b>Role:</b></label>
    <div class="col-sm-9 column mb-3" id="container_roles" style="margin-left:13px;">
        <?php foreach ($roles as $key => $value_role) {
            if ($value_role['code'] !== 'admin') { ?>
                <div class="col-sm-4 form-check">
                    <input
                        class="form-check-input" 
                        type="radio" 
                        name="role"
                        id="<?php echo $value_role['code'] ?>" 
                        value="<?php echo $value_role['id'] ?>" 
                        onclick="setValue(this.value)"
                    >
                    <label 
                        class="form-check-label" 
                        for="<?php echo $value_role['code'] ?>">
                        <?php echo $value_role['name'] ?>
                    </label>
                </div>
        <?php }
            
            }
            ?>
             <?php if (!empty($role_err)) { ?>
        <span class="text-danger my-2"><?php echo $role_err ?></span>
             <?php } ?>
    </div>
    </div>
                            
    <div class="form-group row">
    <div style="margin:auto;width:85%;border:0px solid red;">
        <div style="margin:auto;">
        <center>
        <input type="submit" class="btn" value="Submit" style="color: #fff;background-color: #dda314;border-radius: 25px;width:30%;margin:auto;">
        <input type="reset" class="btn" style="color: #fff;background-color: #dda314;border-radius: 25px;width:30%;margin:auto;" value="Reset">
        </center>
        </div>
    </div>
    </div>
            <p style="text-align:center;">Already a member? <a href="login.php" style="color:#422B69;"><b>Login here</b></a>.</b></p>
    </div>
    </div>
    </div>
        </form>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function setValue(value) {
            document.getElementById('role_user').value = value;
        }
    </script>
</body>

</html>
