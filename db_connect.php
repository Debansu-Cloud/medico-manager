<?php
$servername = "sql107.infinityfree.com";
$username = "if0_40622031";
$password = "X8QCYQViQFDlx6q";
$database = "if0_40622031_medico_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>