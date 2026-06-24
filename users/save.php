<?php

include "../auth.php";
include "../db.php";
include "role_check.php";

$name = $_POST['name'];

$email = $_POST['email'];

$password = password_hash(
$_POST['password'],
PASSWORD_DEFAULT
);

$role = $_POST['role'];

$status = $_POST['status'];

$modules = '';

if(isset($_POST['modules']))
{
    $modules = implode(
        ',',
        $_POST['modules']
    );
}

mysqli_query(

$conn,

"INSERT INTO users(

name,
email,
password,
role,
status,
modules

)

VALUES(

'$name',
'$email',
'$password',
'$role',
'$status',
'$modules'

)"

);

header("Location:list.php");
exit;

?>
