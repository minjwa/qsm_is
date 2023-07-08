<?php

require_once "config.php";

// Initialize the session
session_start();

// $UserType = (object) [
//     'username' => 'John',
//     'type' => 'company'
// ];
$UserType = null;


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($link !== false) {
    if ($_SESSION['role']['code'] == 'company') {
        $UserType = (object)[
            'username' => $_SESSION["username"],
            'type' => $_SESSION["role"]['code']
        ];

        $sql_applications = "SELECT * FROM applications WHERE status = 1";
        $result_applications = $link->query($sql_applications);

        $applications = [];

        while ($row = $result_applications->fetch_assoc()) {
            # code...
            $applications[] = [
                "id" => $row["id"],
                "user_id" => $row["user_id"],
                "job_id" => $row["job_id"],
                "result" => $row["result"]
            ];
        }

        foreach ($applications as $key => $application) {
            $sql_application = "SELECT * FROM jobs WHERE id = " . $application['job_id'];
            $result_application = $link->query($sql_application);

            while ($row = $result_application->fetch_assoc()) {
                $applications[$key]["job"] = [
                    'name' => $row['name'],
                    'description' => $row['description']
                ];
            }
        }

        foreach ($applications as $key => $application) {
            $sql_user = "SELECT * FROM users WHERE id = " . $application['user_id'];
            $result_user = $link->query($sql_user);

            while ($row = $result_user->fetch_assoc()) {
                $applications[$key]["user"] = [
                    'username' => $row['username'],
                    'email' => $row['email']
                ];
            }
        }
    } else if ($_SESSION['role']['code'] == 'student') {
        $UserType = (object)[
            'username' => $_SESSION["username"],
            'type' => $_SESSION["role"]['code']
        ];

        $sql_applications = "SELECT * FROM applications WHERE result = 'accept' OR result = 'reject'";
        $result_applications = $link->query($sql_applications);

        $applications_accept = [];

        while ($row = $result_applications->fetch_assoc()) {
            # code...
            $applications_accept[] = [
                "id" => $row["id"],
                "user_id" => $row["user_id"],
                "job_id" => $row["job_id"],
                "result" => $row["result"]
            ];
        }

        foreach ($applications_accept as $key => $application) {
            $sql_jobs = "SELECT * FROM jobs WHERE id = " . $application['job_id'];
            $result_jobs = $link->query($sql_jobs);

            while ($row = $result_jobs->fetch_assoc()) {
                $applications_accept[$key]["job"] = [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'location_id' => $row['location_id']
                ];
            }
        }

        foreach ($applications_accept as $key => $application) {
            $sql_location = "SELECT * FROM locations WHERE id = " . $application['job']['location_id'];
            $result_location = $link->query($sql_location);

            while ($row = $result_location->fetch_assoc()) {
                $applications_accept[$key]["location"] = [
                    'name' => $row['name'],
                ];
            }
        }

        $sql_current_user = "SELECT * FROM users WHERE id = " . $_SESSION['id'] . " LIMIT 1";
        $result_current_user = $link->query($sql_current_user);

        $user = [];

        while ($row = $result_current_user->fetch_assoc()) {
            # code...
            // error document is not there
            $user["document"] = $row['name'];
        }

        $sql_current_user_logbook = "SELECT * FROM logbooks WHERE user_id = " . $_SESSION['id'] . " LIMIT 1";
        $result_current_user_logbook = $link->query($sql_current_user_logbook);

        while ($row = $result_current_user_logbook->fetch_assoc()) {
            # code...
            $user["logbook"] = [
                "id" => $row['id'],
                "name" => $row['name'],
                "file_dir" => $row["file_dir"],
                'mark' => $row['mark']
            ];
        }

        $sql_supervisors = "SELECT * FROM users WHERE role_id = 3 LIMIT 1";
        $result_supervisors = $link->query($sql_supervisors);

        $supervisors = [];

        while ($row = $result_supervisors->fetch_assoc()) {
            # code...
            $supervisors[] = [
                "id" => $row['id'],
                "username" => $row['username']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

        .card {
           background-color: #FAEBD7;
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
            <img src="img/hr2eazy.png" alt="hr2eazy" style="width: 30%;">
    </div>
    <div class="col-8">
        <h1 class="text-center mt-3">HR2EAZY</h1>
    </div>

    <div class="col-2 text-right">
    <div class="dropdown mt-1">
            <a href="#" id="user-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="img/iconn.png" alt="uitm_logo" style="width: 30%;">
            </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="logout.php">Logout</a>
    </div>
    </div>
            <span class="text-right">
                <b>
                    <?php if($_SESSION["role"]["name"] == "Student"){?>
                        User
                    <?php }?>
                    <?php if($_SESSION["role"]["name"] == "Company"){?>
                        Admin
                    <?php }?>
                </b>
            </span>
    </div>
    </div>

    <div class="row" style="height: 100%;">
    <div class="col-2 py-4" style="border-right: 2px solid #000000;">
        <ul class="no-bullets">
                <li>
     <div class="card">
            <a href="home.php" class="card-body btn btn-warning">
                 Home
            </a>
    </div>
                </li>
                <?php if ($_SESSION['role']['code'] === 'student' || $_SESSION['role']['code'] === 'company') { ?>
                <li class="mt-2">
    <div class="card">
            <a href="booking_training.php" class="card-body btn btn-outline-warning">
                 <!-- My Calendar -->
                 <?php if($UserType->type == 'company') echo "Settings"?>
                 <?php if($UserType->type == 'student') echo "My Calendar"?>
            </a>
    </div>
               </li>

               <?php } ?>
               <?php if ($_SESSION['role']['code'] !== 'company' && $_SESSION['role']['code'] !== 'admin' && $_SESSION['role']['code'] !== 'student') { ?>
               <li class="mt-2">
    <div class="card">
            <a href="logbook_report_page.php" class="card-body btn btn-outline-warning">
                 Logbook
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

            
                </li>
                <?php } ?>
                </ul>
    </div>

    <div class="col-10">
    <div class="d-flex justify-content-center">

             <h1>
                 Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
            </h1>
    </div>

    <div class="mt-5 text-center " style="font-size: 2rem;">
        Applications
    </div>
    <?php if ($_SESSION['role']['code'] == 'company') { ?>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student email</th>
                        <th>Company Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   <?php $x = 0; ?>
                   <?php foreach ($applications as $key => $application) { ?>
                    <tr>
                        <td><?php echo ++$x; ?>.</td>
                        <td>
                   <?php echo $application['user']['email']; ?>
                        </td>
                        <td>
                   <?php echo $application['job']['name']; ?>
                        </td>
                        <td>
                    <?php if ($application['result'] == null) { ?>
        <button class="btn btn-success" onclick="resultApplicant('#formResultApplication<?php echo $application['id']; ?>', '<?php echo $application['id']; ?>')">Result</button>
            <form action="result_application.php" method="POST" id="formResultApplication<?php echo $application['id']; ?>">
            <input type="hidden" id="type_application_<?php echo $application['id'] ?>" name="type_application">
            <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
             </form>
                    <?php } else { ?>
                    <?php echo $application['result']; ?>
                    <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
            </tbody>
            </table>
        </div>
    <?php } ?>
                <?php if ($_SESSION['role']['code'] == 'student') { ?>
    <div class="d-flex justify-content-center mt-4">
    <div class="col-md-12">
    <div class="card">
    <div class="card-header">
             <h2>
                 RESULT
             </h2>
    </div>

    <div class="card-body text-center">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>LOCATION</th>
                    <th>JOB APPLICATION</th>
                    <th>RESULT</th>
                </tr>
            </thead>

            <tbody>
                <?php $x = 0; ?>
                <?php foreach ($applications_accept as $key => $application_accept) { ?>
                <tr>
                <td><?php echo ++$x; ?>.</td>
                <td>
                    <?php echo $application_accept['location']['name']; ?>
                </td>
                <td>
                    <?php echo $application_accept['job']['name']; ?>
                </td>
                <td>
                    <?php echo $application_accept['result']; ?>
                </td>
                </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
    </div>
    </div>

    <div class="d-flex justify-content-center mt-2">
    <div class="col-md-12">
    <div class="card">
    <div class="card-header">
            <h2>
                UPLOAD DOCUMENT
            </h2>
    </div>

    <div class="card-body text-center">
        <?php if ($user["document"] == null) { ?>
            <form action="upload_document.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
                <div class="row">
                    <div class="col-md-10">
                            <input type="file" name="document" class="form-control">
                    </div>

                    <div class="col-md-2">
                            <button type="submit" class="btn btn-warning">Upload</button>
                    </div>
                </div>
            </form>
        <?php } else{ ?>
            <div class="col-md-12">
                <table class="table">


                    <thead>
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>document</td>
                            <td>
                                <button type="button" onclick="deleteDocument('#formDocument')" class="btn btn-primary">Delete</button>
                                <form action="update_document.php" id="formDocument" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
                                    <input type="hidden" name="file_dir" value="<?php echo $user['document']; ?>">
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
         <?php } ?>           
    </div>
    </div>
    </div>
    </div>

    <div class="d-flex justify-content-center mt-2">
    <div class="col-md-12">
    <div class="card">
    <div class="card-body text-center">
                    <?php if (!isset($user['logbook'])) { ?>

        <?php } ?>
        

    <?php if (isset($user['logbook'])) { ?>
    <div class="col-md-12">
        <table class="table">


            <thead>
                <tr>
                    <th>#</th>
                    <th>name</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1.</td>
                    <td>logbook</td>
                    <td>
            <button type="button" onclick="deleteLogbook('#formLogbook')" class="btn btn-primary">Delete</button>
            <form action="update_logbook.php" id="formLogbook" method="POST">
            <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
            <input type="hidden" name="file_dir" value="<?= $user['logbook']['file_dir']; ?>">
            </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php } ?>

    </div>
    </div>
    </div>`
    </div>
                    <?php  if ($_SESSION['role']['code'] == 'company') { ?>
   
    </div>
    </div>
    </div>
    </div>
                   <?php } ?>
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
        <?php if ($_SESSION['role']['code'] == 'company') { ?>

            function resultApplicant(form_id, application_id) {
                swal({
                        title: "Are you sure?",
                        text: "Accept or Reject",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((result) => {
                        if (result) {
                            $('#type_application_' + application_id).val('accept');
                            $(form_id).submit();
                        } else {
                            $('#type_application_' + application_id).val('reject');
                            $(form_id).submit();
                        }
                    });
            }
        <?php } else { ?>

            function deleteDocument(form_id) {
                swal({
                        title: "Are you sure?",
                        text: "Delete Booking",
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
            function deleteLogbook(form_id) {
                swal({
                        title: "Are you sure?",
                        text: "Delete Logbook",
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
        <?php } ?>

    </script>
</body>

</html>