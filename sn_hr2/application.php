<?php
// Start the session
session_start();

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['role']['code'] !== 'student') {
    // Redirect the user to the login page or show an error message
    header("Location: login.php");
    exit;
}

// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'qsm_is');

// Create a new mysqli instance
$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get the form data
  $userId = $_POST['user_id'];
  $status = $_POST['status'];
  $dateBookings = $_POST['date_booking']; // Use array for date_booking
  $descriptionIds = $_POST['description']; // Use array for description
  $jobIds = $_POST['job_id']; // Use array for job_id

  $sqlselect = "SELECT * FROM description WHERE name = '".$descriptionIds."'";
  $query = mysqli_query($link, $sqlselect);
  $row = mysqli_fetch_array($query);
  $test = $row['id'];

  // Validate and sanitize the form data as needed

  // Check the connection
  if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
  }

// Prepare and execute the SQL query to insert the form data into the database
$sql = "INSERT INTO applications (user_id, job_id, description, date_booking, status) VALUES ('".$userId."', '".$jobIds."', '".$row['id']."', '".$dateBookings."', '0')";
mysqli_query($link, $sql);

// Redirect the user to a success page or show a success message
header("Location: home.php");
exit;
}