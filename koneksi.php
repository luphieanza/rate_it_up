<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "db_rateitup";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>