<?php

include "../auth.php";
include "../db.php";
include "common_functions.php";

$id=$_GET['id'];

mysqli_query(
$conn,
"DELETE FROM customers
WHERE id='$id'"
);

header("Location:list.php");
exit;

