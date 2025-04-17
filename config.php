<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "data";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
