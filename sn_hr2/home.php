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
                "status" => $row["status"]
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

                $applications[$key]["job"] = [
                    'name' => $row['name'],
                    'description' => $row['description'],

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

        $sql_applications = "SELECT * FROM applications WHERE status = '0' OR status = '1' OR status = '2'";
        $result_applications = $link->query($sql_applications);

        $applications_accept = [];

        while ($row = $result_applications->fetch_assoc()) {
            # code...
            $applications_accept[] = [
                "id" => $row["id"],
                "user_id" => $row["user_id"],
                "job_id" => $row["job_id"],
                "result" => $row["status"],
                "description" => $row["description"]
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

        // $sql_current_user = "SELECT * FROM users WHERE id = " . $_SESSION['id'] . " LIMIT 1";
        // $result_current_user = $link->query($sql_current_user);

        // $user = [];

        // while ($row = $result_current_user->fetch_assoc()) {
        //     # code...
        //     // error document is not there
        //     $user["document"] = $row['name'];
        // }

        $sql_document_current_user = "SELECT * FROM document WHERE user_id = " . $_SESSION['id'] . " LIMIT 1";
        $result_document_current_user = $link->query($sql_document_current_user);

        $user = [];

        while ($row = $result_document_current_user->fetch_assoc()) {
            # code...
            // error document is not there
            $user["document"] = [
                "name" => $row['name'], 
            ];
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
            background:#eaedf6;
        }

        h2 {
            font-size:1.4rem;
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
           background-color: #fff;
        }

        .btn {
            background:transparent;
            color:#000;
            border:1px solid transparent;
            width:100%;
        }

        .btn2 {
            background:#41a764;
            color:#fff;
        }

        .btn2:hover {
            background:red;
        }

        .btn3 {
            background:white;
        }

        .btn4 {
            background:#41a764;
            color:#fff;
        }

        .active, .btn:hover {
            background:#ffc107;
            color:#fff;
            border:1px solid transparent;
          border:1px solid #fff;
        }

        .table {
            background:#fff;
            border:0px solid red;
            border-radius:10px;
        }

        .table th {
            background:#303666;
            color:#fff;
            font-weight:normal;
        }

        .table tr {
            
        }

        .table td {
            border-bottom:1px solid #f2f2f2;
        }

    </style>
</head>

<body>
    <div class="container-fluid" style="height: 100%;">
    <div class="row" style="background:#fff;box-shadow:0px 3px 15px 0px #dbdbdb;padding:10px;">
    <div class="col-2">
            <img src="img/hr2eazy.png" alt="hr2eazy" style="width: 40%;">
    </div>
    <div class="col-8">
        <h1 class="text-center mt-3">Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
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
            <span style="margin-right:10px;">
                <b>
                    <?php if($_SESSION["role"]["name"] == "Student"){?>
                        <span style="margin-right:5px;">User</span>
                    <?php }?>
                    <?php if($_SESSION["role"]["name"] == "Company"){?>
                        Admin
                    <?php }?>
                </b>
            </span>
    </div>
    </div>

    <div class="row" style="height: 100%;">
    <div class="col-2 py-4" style="border-right: 0px solid #000000;background:#085e81;">
        <ul class="no-bullets">
                <li>
     <div class="card">
            <a href="home.php" class="card-body btn active">
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
               <?php if ($_SESSION['role']['code'] === 'company') { ?>
                        <li class="mt-2">
                            <div class="card">
                                    <a href="users_page.php" class="card-body btn btn-outline-warning">
                                        User
                                    </a>
                            </div>
                        </li>
                    <?php } ?>

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

    </div>

    <div class="row bg-white my-4 mx-1 py-3" style="color: #303666; border-radius:10px;">
        <div class="col">
            <div class="row">
                <div class="col text-center" style="font-size: 1.3rem;">
                    <span>Manual</span>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-2">
                    <button type="button" class="btn border bg-primary text-white">Upload Document</button>    
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Document 1</h4>
                            <button type="button" class="btn bg-primary text-white">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5 text-center" style="font-size: 1.3rem;background:#fff;padding:15px;border-radius:10px;color:#303666;">
        Applications
    </div>


<?php if ($_SESSION['role']['code'] == 'company') { ?>
    <?php
    // Fetch and store the application data
    $sql_applications = "SELECT a.id, a.user_id, a.job_id, a.description, d.name AS description_name, a.date_booking, a.status, u.username, j.name AS job_name
    FROM applications AS a
    JOIN users AS u ON a.user_id = u.id
    JOIN jobs AS j ON a.job_id = j.id
    JOIN description AS d ON a.description = d.id"; // Join the 'description' table
    $result_applications = $link->query($sql_applications);

    if (!$result_applications) {
        // Handle the query error
        echo "Error: " . $link->error;
        exit;
    }

    $applications = [];

    while ($row = $result_applications->fetch_assoc()) {
        $applications[] = [
            "id" => $row["id"],
            "user_id" => $row["user_id"],
            "username" => $row["username"], // Adding the 'username' field from the 'users' table
            "description" => $row["description_name"],
            "job_id" => $row["job_id"],
            "job_name" => $row["job_name"], // Adding the 'job_name' field from the 'jobs' table
            "date_booking" => $row["date_booking"],
            "status" => $row["status"],
        ];
    }
    ?>

    <div class="mt-3">
    <div class="col-md-12">
    <div class="card">
    <div class="card-body">
        <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Company Name</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>Date Booking</th>
                    <th><center>Action</center></th>
                </tr>
            <tbody>
                <?php $x = 0; // Initialize $x variable ?>
                <?php foreach ($applications as $application) { ?>
                    <tr>
                        <td><?php echo ++$x; ?>.</td>
                        <td><?php echo $application['job_name']; ?></td>
                        <td><?php echo $application['username']; ?></td>
                        <td><?php echo $application['description']; ?></td>
                        <td><?php echo $application['date_booking']; ?></td>
                        <td>
                            <?php if ($application['status'] == '0') { ?>
                                <!-- Output the button and form for the action -->
                                <button class="btn btn-success" onclick="resultApplicant('<?php echo $application['id']; ?>')"><center>Result</center></button>
                                <form action="result_application.php" method="POST" id="formResultApplication_<?php echo $application['id']; ?>">
                                    <input type="hidden" id="type_application_<?php echo $application['id']; ?>" name="type_application" value="">
                                    <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                </form>
                            <?php } else if ($application['status'] == '1') { ?>
                                <span class="text-success"><center>Accept</center></span>
                            <?php } else if ($application['status'] == '2') { ?>
                                <span class="text-danger"><center>Reject</center></span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
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
                    <th>No.</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Company</th>
                    <th>Result</th>
                </tr>
            </thead>

            <tbody>
            <?php $x = 0; ?>
            <?php foreach ($applications_accept as $key => $application_accept) { 
                // Check if the user_id of the application matches the session user_id
                if ($application_accept['user_id'] == $_SESSION['id']) {
            ?>
                <tr>
                    <td><?php echo ++$x; ?>.</td>
                    <td>
                        <?php echo $application_accept['location']['name']; ?>
                    </td>
                    <td>
                        <?php
                        $description_id = $application_accept['description'];
                        $sql_description = "SELECT name FROM description WHERE id = $description_id";
                        $result_description = $link->query($sql_description);
                        $row_description = $result_description->fetch_assoc();
                        echo $row_description['name'];
                        ?>
                    </td>
                    <td>
                        <?php echo $application_accept['job']['name']; ?>
                    </td>
                    <td>
                        <?php
                        $result = $application_accept['result'];
                        $result_display = '';

                        if ($result == 0) {
                            $result_display = '<div style="background-color: orange; color: white; padding: 5px; width:100px;margin:auto;">PENDING</span>';
                        } elseif ($result == 1) {
                            $result_display = '<div style="background-color: #41a764; color: white; padding: 5px; width:100px;margin:auto;">ACCEPT</span>';
                        } elseif ($result == 2) {
                            $result_display = '<div style="background-color: red; color: white; padding: 5px; width:100px;margin:auto;">REJECT</span>';
                        }

                        echo $result_display;
                        ?>
                    </td>
                </tr>
            <?php 
                } // End of the if condition for user_id check
            } // End of the foreach loop for applications_accept
            ?>

            </tbody>
        </table>
    </div>
    </div>
    </div>
    </div>
    </div>

        <div class="d-flex justify-content-center mt-4">
        <div class="col-md-12">
        <div class="card">
        <div class="card-header">
                <h2>
                    UPLOAD DOCUMENT
                </h2>
        </div>

        <div class="card-body text-center">
            <?php if (sizeof($user) == 0) { ?>
                <form action="upload_document.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
                    <div class="row">
                        <div class="col-md-10">
                                <input type="file" name="document" class="form-control" style="width:50%;">
                        </div>

                        <div class="col-md-2">
                                <button type="submit" class="btn" style="margin-right:40%;float:right;">Upload</button>
                        </div>
                    </div>
                </form>
            <?php } else{ ?>
                <div class="col-md-12">
                    <table class="table">


                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>FILE NAME</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <?php foreach($user as $key => $item) {?>
                                    <td>1.</td>
                                    <td> <?php echo $item['name']; ?> </td>
                                    <td>
                                        <button type="button" onclick="deleteDocument('#formDocument')" class="btn btn-primary">Delete</button>
                                        <form action="update_document.php" id="formDocument" method="POST">
                                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
                                            <input type="hidden" name="file_dir" value="<?php echo $item['name']; ?>">
                                            <!-- <input type="hidden" name="file_dir" value="<?php echo $user['document']; ?>"> -->
                                        </form>
                                    </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>           
        </div>
        </div>
        </div>
        </div>

        <div class="d-flex justify-content-center mt-4" style="margin-bottom:100px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>FEEDBACK FORM</h2>
                </div>

                <div class="card-body text-center">
                    <form action="home.php" method="POST">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Rating Scale (1-5)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Question 1: How satisfied are you with our service?</td>
                                    <td>
                                        <select name="question1">
                                            <option value="1">1 (Not Satisfied)</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5 (Very Satisfied)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question 2: How likely are you to recommend us to others?</td>
                                    <td>
                                        <select name="question2">
                                            <option value="1">1 (Not Likely)</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5 (Very Likely)</option>
                                        </select>
                                    </td>
                                </tr>
                                <!-- Add three more questions here following the same pattern -->
                                <tr>
                                    <td>Question 3: ...</td>
                                    <td>
                                        <select name="question3">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question 4: ...</td>
                                    <td>
                                        <select name="question4">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question 5: ...</td>
                                    <td>
                                        <select name="question5">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="submit" style="cursor: pointer">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    

   
                        <?php } ?>
                    <?php  if ($_SESSION['role']['code'] == 'company') { ?>
   
    
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

            function resultApplicant(applicationId) {
                swal({
                    title: "Are you sure?",
                    text: "Accept or Reject",
                    icon: "warning",
                    buttons: {
                        accept: {
                            text: "Accept",
                            value: "accept",
                        },
                        reject: {
                            text: "Reject",
                            value: "reject",
                        },
                    },
                    dangerMode: true,
                }).then((result) => {
                    if (result === "accept") {
                        $('#type_application_' + applicationId).val('1'); // Set status value as '1' for accept
                        $('#formResultApplication_' + applicationId).submit();
                    } else if (result === "reject") {
                        $('#type_application_' + applicationId).val('2'); // Set status value as '2' for reject
                        $('#formResultApplication_' + applicationId).submit();
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