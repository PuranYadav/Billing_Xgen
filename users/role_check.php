<?php

if(
$_SESSION['role'] == 'Admin'
||
$_SESSION['role'] == 'Super Admin'
)
{
    return;
}

if(
empty($_SESSION['modules'])
)
{
    header("Location:../dashboard.php");
    exit;
}

if(
!in_array(
'users',
$_SESSION['modules']
)
)
{
    header("Location:../dashboard.php");
    exit;
}

?>