<?php
// Initialize the session
session_start();

require 'config.php';

// Initialize variable 
$locations = [];
$jobs = [];
$descriptions = [];
$name = $location_id = '';
$name_err = $description_err = $location_id_err = '';

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
} else {

    if ($link !== false) {

        if(isset($_POST['job_id']) && isset($_POST['user_id']) && isset($_POST['status']) && isset($_POST['date_booking'])){
            // Construct the SQL query to update the date_booking and status columns in the jobs table
            $sql_jobs = "UPDATE jobs SET date_booking = '" . trim($_POST['date_booking']) . "', status = 0 WHERE id = " . trim($_POST['job_id']);
        
            // Execute the SQL query
            if ($link->query($sql_jobs) === TRUE) {
                echo "Existing record updated successfully";
            } else {
                echo "Error: " . $sql_jobs . "<br>" . $link->error;
            }
        }

        $sql_locations = "SELECT * FROM locations";
        $result_locations = $link->query($sql_locations);

        while ($row = $result_locations->fetch_assoc()) {
            # code...
            $locations[] = [
                "id" => $row["id"],
                "name" => $row["name"],
            ];
        }

        $sql_select_jobs = "SELECT jobs.id, jobs.name, jobs.location_id, description.name AS description
        FROM jobs 
        INNER JOIN description ON jobs.description = description.id";
        $result_jobs = $link->query($sql_select_jobs);

        while ($row = $result_jobs->fetch_assoc()) {
        $jobs[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "location_id" => $row['location_id'],
        "description" => $row['description'],
        ];
        }

        $sql_descriptions = "SELECT * FROM description";
        $result_descriptions = $link->query($sql_descriptions);

        while($row = $result_descriptions->fetch_assoc()){
            $descriptions[] = [
                "id" => $row["id"],
                "name" => $row["name"],
            ];
        }

        foreach ($jobs as $key => $job) {

            $sql_select_location = "SELECT * FROM locations WHERE id = " . $jobs[$key]['location_id'] . " LIMIT 1";

            $location = [
                "id" => '',
                "name" => ''
            ];

            $result_location = $link->query($sql_select_location);

            while ($row_location = $result_location->fetch_assoc()) {
                $location["id"] =  $row_location["id"];
                $location["name"] =  $row_location["name"];
            }

            $jobs[$key]['location'] = $location;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Settings</title>
    <style>
        html,
        body {
            height: 100%;
            background:#eaedf6;
        }

        h2 {
            font-size:1.5rem;
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
            background:#3a7bfd;
            color:white;
        }

        .btn4 {
            background:#41a764;
            color:#fff;
        }

        .active, .btn:hover {
            background:#ffc107;
            color:#fff;
            border:1px solid transparent;
        }  border:1px solid #fff;
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
                <img src="img/hr2eazy.png" alt="hr2eazy " style="width: 40%;">
    </div>
    
    <div class="col-8">
                <h1 class="text-center mt-3"></h1>
    </div>
            
    <div class="col-2 text-right">
    <div class="dropdown mt-1">
                    <a href="#" id="user-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="img/iconn.png" alt="iconn_logo" style="width: 30%;">
                    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="logout.php">Logout</a>
    </div>
    </div>
                <span class="text-right">
                    <b>
                        <?php if($_SESSION["role"]["name"] == "Student"){?>
                            <span style="margin-right:15px;">User</span>
                        <?php }?>
                        <?php if($_SESSION["role"]["name"] == "Company"){?>
                            <span style="margin-right:10px;">Admin</span>
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
                            <a href="home.php" class="card-body btn">
                                Home
                            </a>
                        </div>
                    </li>
                    <!-- <?php if ($_SESSION['role']['code'] !== 'company' || $_SESSION['role']['code'] !== 'admin') { ?>
                        <li class="mt-2">
                            <div class="card">
                                    <a href="booking_training.php" class="card-body btn btn-warning">
                                        My Calendar
                                    </a>
                            </div>
                        </li>
                   
                    <?php } ?> -->
                    <?php if ($_SESSION['role']['code'] === 'student') { ?>
                        <li class="mt-2">
                            <div class="card">
                                    <a href="booking_training.php" class="card-body btn active">
                                        My Calendar
                                    </a>
                            </div>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION['role']['code'] === 'company') { ?>
                        <li class="mt-2">
                            <div class="card">
                                    <a href="booking_training.php" class="card-body btn active">
                                        Settings
                                    </a>
                            </div>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION['role']['code'] === 'company') { ?>
                        <li class="mt-2">
                            <div class="card">
                                    <a href="users_page.php" class="card-body btn btn-outline-warning">
                                        User
                                    </a>
                            </div>
                        </li>
                    <?php } ?>
                    <!-- <?php if ($_SESSION['role']['code'] === 'admin') { ?>
                        <li class="mt-2">
                            <div class="card">
                                    <a href="users_page.php" class="card-body btn btn-outline-warning">
                                        Users
                                    </a>
                            </div>
                        </li>
                    
                    
                        </div>
                        </li>
                    <?php } ?> -->
                </ul>
            </div>
            <div class="col-10">
            <div class="d-flex justify-content-center">
        
            </div>

            <div class="mt-5 text-center" style="font-size: 1.3rem;background:#fff;padding:15px;border-radius:10px;color:#303666;">
    Company List
</div>
            <?php if ($_SESSION['role']['code'] == 'company') { ?>


    <div class="d-flex mt-3">

        <div class="col-md-12">

            <div class="card">

            <div class="d-flex flex-row-reverse">
        <button type="button" class="btn btn3" data-toggle="modal" data-target="#createJob" style="margin:20px 20px 0px 0;border:0px solid #f2f2f2;width:15%;">
            Add Company
        </button>
    </div>

                <div class="card-body">
                    <table class="table table-striped">
                            <tr>
                                <th class="col-1">No.</th>
                                <th class="col-3">Name</th>
                                <th class="col-4">Description</th>
                                <th class="col-3">Location</th>
                                <th class="col-1"><center>Action</center></th>
                            </tr>
                        <tbody>
                            <?php if (count($jobs) != 0) {
                                $x = 0;
                                foreach ($jobs as $key => $job) { ?>
                                    <tr>
                                        <td><?php echo ++$x ?>.</td>
                                        <td><?php echo $job['name'] ?></td>
                                        <td><?php echo $job['description'] ?></td>
                                        <td><?php echo $job['location']['name'] ?></td>
                                        <td>
                                            <form action="delete_job.php" method="POST">
                                                <input type="hidden" name="job_id" value="<?php echo $job['id'] ?>">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-trash"></i></button>
                                            </form>
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editJobModal<?php echo $job['id'] ?>"><i class="fa fa-edit"></i></button>
                                            <!-- Modal and Edit Form Code Here -->
                                        </td>
                                    </tr>
                            <?php }
                            } else { ?>
                                <tr>
                                    <td align="center" colspan="3">No Data Found</td>
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
    <div class="d-flex mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                            <tr>
                                <th class="col-1">No.</th>
                                <th class="col-3">Name</th>
                                <th class="col-4">Description</th>
                                <th class="col-3">Location</th>
                                <th class="col-1"><center>Action</center></th>
                            </tr>
                        <tbody>
                            <?php if (count($jobs) != 0) {
                                $x = 0;
                                foreach ($jobs as $key => $job) { ?>
                                    <tr>
                                        <td><?php echo ++$x ?>.</td>
                                        <td><?php echo $job['name'] ?></td>
                                        <!-- Display the description from the $jobs array -->
                                        <td><?php echo $job['description']; ?></td>
                                        <td><?php echo $job['location']['name'] ?></td>
                                        <td>
                                            <form action="application.php" id="formapplication<?php echo $job['id'] ?>" method="POST">
                                                <!-- <input type="hidden" name="=""> Use array for job_id -->
                                                <input type="hidden" name="job_id" value = "<?php echo $job['id'] ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $_SESSION["id"] ?>">
                                                <input type="hidden" name="description" value="<?php echo $job['description'] ?>">
                                                <input type="hidden" name="status[]" value="0"> <!-- Use array for status -->
                                                    <label for="date_booking">Booking Date</label>
                                                    <input id="date_booking" type="date" name="date_booking" value="<?php echo $application['date_booking'] ?>">
                                                <button type="button" class="btn btn-success mt-4" onclick="applyAlert('#formapplication<?php echo $job['id'] ?>')">Apply</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php }
                            } else { ?>
                                <tr>
                                    <td align="center" colspan="3">No Data Found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
     
    <?php if ($_SESSION['role']['code'] == 'company') { ?>
    <div class="modal fade" id="createJob" tabindex="-1" role="dialog" aria-labelledby="createJobLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="createJobLabel">Add Company Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
            
            <form id="formJob" action="create_job.php" method="post">
                    <div class="modal-body">
                    <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                        <span class="text-danger" id="name_err"></span>
                    </div>
                                    
                    <div class="form-group mt-2">
                        <label for="name">Description</label>
                        <select name="description" id="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>">
                        <option value="">-- Please Select --</option>
                        <?php foreach ($descriptions as $description) { ?>
                                    <option value="<?php echo $description['id'] ?>"><?php echo $description['name'] ?></option>
                            <?php } ?>
                        </select>
                                <span class="text-danger" id="description_err"></span>

                    </div>
                                    
                    <div class="form-group mt-2">
                        <label for="name">Location</label>
                        <select name="location_id" id="location_id" class="form-control <?php echo (!empty($location_id_err)) ? 'is-invalid' : ''; ?>">
                        <option value="">-- Please Select --</option>
                            <?php foreach ($locations as $location) { ?>
                                    <option value="<?php echo $location['id'] ?>"><?php echo $location['name'] ?></option>
                            <?php } ?>
                        </select>
                                <span class="text-danger" id="location_id_err"></span>
                    </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        <button type="button" onclick="validateForm('create')" class="btn btn-warning">Save changes</button>
                    </div>
                    </form>
                    </div>
    </div>
    </div>
        <?php } ?>
    </div>
    <!-- <h1 class="my-5">. Welcome to our site.</h1>
    <p> -->
    <!-- <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a> -->
    <!-- <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p> -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        <?php if ($_SESSION['role']['code'] == 'company') { ?>

            function validateForm(form_type) {

                var error_count = 0;

                if (form_type == 'create') {

                    if ($.trim($('#name').val()) == '') {
                        $('#name_err').text('Please enter name');
                        ++error_count;
                    } else {
                        $('#name_err').text('');
                    }

                    if ($.trim($('#description').val()) == '') {
                        $('#description_err').text('Please enter description');
                        ++error_count;
                    } else {
                        $('#description_err').text('');
                    }

                    if ($.trim($('#location_id').val()) == '') {
                        $('#location_id_err').text('Please choose location');
                        ++error_count;
                    } else {
                        $('#location_id_err').text('');
                    }
                } else if (form_type == 'update') {
                    if ($.trim($('#name_edit').val()) == '') {
                        $('#name_err_edit').text('Please enter name');
                        ++error_count;
                    } else {
                        $('#name_err_edit').text('');
                    }

                    if ($.trim($('#description_edit').val()) == '') {
                        $('#description_err_edit').text('Please enter description');
                        ++error_count;
                    } else {
                        $('#description_err_edit').text('');
                    }

                    if ($.trim($('#location_id_edit').val()) == '') {
                        $('#location_id_err_edit').text('Please choose location');
                        ++error_count;
                    } else {
                        $('#location_id_err_edit').text('');
                    }
                }


                if (error_count > 0) {
                    return;
                } else {
                    if (form_type == 'create') {
                        $('#formJob').submit();
                    } else if (form_type == 'update') {
                        $('#formEditJob').submit();
                    }
                }
            }
        <?php } else if ($_SESSION['role']['code'] == 'student') { ?>

            function applyAlert(form_id) {
                swal({
                        title: "Are you sure?",
                        text: "Book Training",
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