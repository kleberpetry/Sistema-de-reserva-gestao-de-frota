<?php
$servername = "localhost";
$database = "senais88_reserva";
$username = "senais88_reserva";
$password = "cJ5iLERE1s3wDfgf";

$conn = new mysqli($servername, $username, $password, $database);

if (!$conn) {
  die(mysqli_connect_error());
}
?>