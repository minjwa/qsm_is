<?php
// Initialize the session
session_start();

require 'config.php';

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
} else {
    if ($link !== false) {
        $sql_users = "SELECT * FROM users";
        $result_users = $link->query($sql_users);

        $users = [];

        while ($row = $result_users->fetch_assoc()) {
            # code...
            $users[] = [
                "id" => $row["id"],
                "email" => $row["email"],
                "username" => $row["username"],
                "role_id" => $row["role_id"],
                "role" => []
            ];
        }

        $sql_roles = "SELECT * FROM roles WHERE code IN ('student', 'company', 'supervisor')";
        $result_roles = $link->query($sql_roles);

        $roles = [];

        while ($row = $result_roles->fetch_assoc()) {
            # code...
            $roles[] = [
                "id" => $row["id"],
                "name" => $row["name"],
            ];
        }

        foreach ($users as $key => $user) {
            $sql_roles = "SELECT * FROM roles WHERE id = ".$user['role_id'] . " LIMIT 1";
            $result_roles = $link->query($sql_roles);

            while ($row = $result_roles->fetch_assoc()) {
                # code...
                $users[$key]['role'] = [
                    "id" => $row["id"],
                    "code" => $row["code"],
                    "name" => $row["name"]
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Logbook and Report</title>
    <style>
        html,
        body {
            height: 100%;
        }

        ul.no-bullets {
            list-style-type: none;
            /* Remove bullets */
            padding: 0;
            /* Remove padding */
            margin: 0;
            /* Remove margins */
        }
    </style>

    <style>
    body  {

    background-color: #FAEBD7;
    }
    </style>

</head>

<body>
    <div class="container-fluid" style="height: 100%;">
    <div class="row mt-2" style="border-bottom: 2px solid #000000;">
    <div class="col-2">
        <img src="img/hr2eazy.png" alt="hr2eazy_logo" style="width: 100%;">
    </div>
    
    <div class="col-8">
        <h1 class="text-center mt-3">INTERNSHIP SYSTEM</h1>
    </div>
            
    <div class="col-2 text-right">
    <div class="dropdown mt-1">
        <a href="#" id="user-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="img/avatar.png" alt="hr2eazy_logo" style="width: 30%;">
        </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="logout.php">Logout</a>
    </div>
    </div>
    <span class="text-right"><b><?php echo $_SESSION["role"]["name"] ?></b></span>
    </div>
    </div>

    <div class="row" style="height: 100%;">
    <div class="col-2 py-4" style="border-right: 2px solid #000000;">
        <ul class="no-bullets">
        <li>
    <div class="card">
        <a href="home.php" class="card-body btn btn-outline-warning">
             Home
        </a>
    </div>
        </li>
                    
         <?php if ($_SESSION['role']['code'] === 'student' || $_SESSION['role']['code'] === 'company') { ?>
        <li class="mt-2">
    <div class="card">
        <a href="booking_training.php" class="card-body btn btn-outline-warning">
             Booking Training
        </a>
    </div>
        </li>
        <?php } ?>
                    
        <?php if ($_SESSION['role']['code'] !== 'company' && $_SESSION['role']['code'] !== 'admin') { ?>
        <li class="mt-2">
    <div class="card">
        <a href="logbook_report_page.php" class="card-body btn btn-outline-warning">
             Logbook and report
        </a>
    </div>
        </li>
        <?php } ?>
                    
        <?php if ($_SESSION['role']['code'] === 'admin') { ?>
        <li class="mt-2">
    <div class="card">
        <a href="users_page.php" class="card-body btn btn-outline-warning">
             Users
        </a>
    </div>
        </li>
        <?php } ?>
                    
        <?php if ($_SESSION['role']['code'] === 'supervisor') { ?>
        <li class="mt-2">
    <div class="card">
        <a href="internship_student.php" class="card-body btn btn-outline-warning">
             Internship Student
        </a>
    </div>
        </li>
        <?php } ?>
         </ul>
        </div>
            
    <div class="col-10">
    <div class="d-flex justify-content-center mt-1">
            <h1>
                <b>Logbook and Report List</b>
            </h1>
    </div>
    
    <div class="d-flex mt-3">
    <div class="col-md-12 text-right">
        <button class="btn btn-lg btn-warning" data-toggle="modal" data-target="#addUser">Add User</button>
    </div>
    </div>
            <div class="d-flex mt-3">
            <div class="col-md-12">
            <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                    <th class="col-1">#</th>
                    <th class="col-4">Name</th>
                    <th class="col-5">Email</th>
                    <th class="col-1">Role</th>
                    <th class="col-1">Action</th>
                    </tr>
                </thead>
                <tbody>
            
                <?php $x = 0; ?>
                <?php foreach ($users as $key => $user) { ?>
                    <tr>
                        <td><?php echo ++$x; ?>.</td>
                    <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']['code']; ?></td>
                        <td>
                                                    
                <?php if ($user['id'] != $_SESSION["id"]) { ?>
                <button type="button" class="btn btn-primary" onclick="deleteAlert('#formUserDelete<?php echo $user['id'] ?>')">Delete</button>
                    <form action="delete_user.php" method="POST" id="formUserDelete<?php echo $user['id'] ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                    </form>
                <?php } ?>
                                                    
                <?php if ($user['id'] != $_SESSION["id"] && $user['role']['code'] == 'student') { ?>
                <button type="button" class="btn btn-warning mt-2" onclick="assignStudent('#formUserAssign<?php echo $user['id'] ?>')">Assign Student to Supervisor</button>
                    <form action="assign_user.php" method="POST" id="formUserAssign<?php echo $user['id'] ?>">
                <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                     </form>
                <?php } ?>
                     </td>
                     </tr>
                <?php } ?>
                                        
                <?php if (count($users) == 0) { ?>
                    <tr>
                    <td align="center" colspan="2">No Data Found</td>
                    </tr>
                <?php } ?>
                    </tbody>
                    </table>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addUserLabel">Edit Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
        </div>
            <form id="formAddUser" action="create_user.php" method="POST">
        <div class="modal-body">
        <div class="col-md-12">
        <div class="form-group">
             <label for="email">Email</label>
             <input type="email" name="email" id="email" class="form-control" value="">
             <span class="text-danger" id="email_err"></span>
        </div>
       
        <div class="form-group mt-2">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username">
                <span class="text-danger" id="username_err"></span>
        </div>
                                
        <div class="form-group mt-2">
            <label for="role">role</label>
            <select name="role_id" id="role_id" class="form-control">
             <option value=""> ...Please Select...</option>
            
             <?php foreach ($roles as $role) { ?>
             <option value="<?php echo $role['id'] ?>"><?php echo $role['name'] ?></option>
             <?php } ?>
            </select>
             <span class="text-danger" id="role_id_err"></span>
        </div>
        </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            <button type="button" onclick="validateForm()" class="btn btn-warning">Create</button>
        </div>
            </form>
        </div>
        </div>
        </div>
    </div>
    <!-- <h1 class="my-5">. Welcome to our site.</h1>
    <p> -->
    <!-- <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a> -->
    <!-- <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p> -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script>
        function validateForm() {
            var error_count = 0;
            if ($.trim($('#email').val()) == '') {
                $('#email_err').text('Please enter email');
                ++error_count;
            } else {
                $('#email_err').text('');
            }

            if ($.trim($('#username').val()) == '') {
                $('#username_err').text('Please enter username');
                ++error_count;
            } else {
                $('#username_err').text('');
            }

            if ($.trim($('#role_id').val()) == '') {
                $('#role_id_err').text('Please choose role');
                ++error_count;
            } else {
                $('#role_id_err').text('');
            }

            if (error_count > 0) {
                return;
            } else {
                $('#formAddUser').submit();
            }
        }

        function validateEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }

        function deleteAlert(form_id) {
            swal({
                    title: "Are you sure?",
                    text: "This data will be delete permanently",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((result) => {
                    if (result) {
                        $(form_id).submit();
                    }
                });
        }

        function assignStudent(form_id) {
            swal({
                    title: "Are you sure?",
                    text: "Assign Student to Supervisor",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((result) => {
                    if (result) {
                        $(form_id).submit();
                    }
                });
        }
    </script>
</body>

</html>