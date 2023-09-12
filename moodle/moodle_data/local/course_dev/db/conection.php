<?php
global $CFG;

$settings_config = (array)get_config('local_course_dev');

/** Test conection  */
$servername = $settings_config['simat_conection_server'];
$username = $settings_config['simat_conection_user'];
$password = $settings_config['simat_conection_password'];
$dbname = $settings_config['simat_conection_db_name'];
$dbnameReporter = $settings_config['simat_conection_db_name'];

// Create connection with simat
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create connection with external
$connReporter = new mysqli($servername, $username, $password, $dbnameReporter);
// Check connection
if ($connReporter->connect_error) {
  die("Connection failed: " . $connReporter->connect_error);
}