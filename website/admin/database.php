<?php
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = "";     // default is empty
$dbname = "flight_booking"; // the DB you imported

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to database successfully";
?>
