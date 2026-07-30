<?php

$servername = "sql201.byethost15.com";
$username   = "b15_41556107";
$password   = "bcgqt97v";
$dbname     = "b15_41556107_DriverMaster";

/*
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "empresa_25161";
*/

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8mb4");

if ($conn->connect_error) {
    die("Ligação à base de dados falhou: " . $conn->connect_error);
}
?>
