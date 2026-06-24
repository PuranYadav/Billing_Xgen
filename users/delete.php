<?php

include "../auth.php";
include "../db.php";


$id =
$_GET['id'];

mysqli_query(
$conn,
"DELETE FROM users
WHERE id='$id'"
);

header("Location:list.php");
exit;