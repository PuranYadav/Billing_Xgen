<?php

include "../auth.php";
include "../db.php";
include "role_check.php";

$id = (int)$_GET['id'];

$modules = '';

if(isset($_POST['modules']))
{
    $modules =
    implode(
        ',',
        $_POST['modules']
    );
}

mysqli_query(

$conn,

"UPDATE users SET

name='".mysqli_real_escape_string($conn,$_POST['name'])."',

email='".mysqli_real_escape_string($conn,$_POST['email'])."',

role='".mysqli_real_escape_string($conn,$_POST['role'])."',

status='".mysqli_real_escape_string($conn,$_POST['status'])."',

modules='".$modules."'

WHERE id='$id'"

);

header("Location:list.php");
exit;

?>
