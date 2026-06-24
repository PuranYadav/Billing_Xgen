<?php

$host = "localhost";
$user = "root";
$pass = "2002";
$db   = "billing_xgen";

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if(!$conn)
{
    die("Database Connection Failed");
}
?>