<?php
// includes/db_connect.php
$servername = "localhost";
$username = "root";
$password = "";
$database = "car_rental_db";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
