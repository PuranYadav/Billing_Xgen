<?php

error_reporting(0);
ini_set('display_errors', 0);

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit;
}