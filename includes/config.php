<?php

$host = "172.31.22.43";
$username = "Jenna200367032";
$password = "5IVzcyJp-J";
$dbname = "db";


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

