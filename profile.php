<?php

include "auth.php";
include "db.php";

$user = mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT *
FROM users
WHERE id=".$_SESSION['user_id']

)

);

?>

<!DOCTYPE html>
<html>

<head>

<title>Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<a href="dashboard.php" class="btn btn-secondary mb-3">
← Dashboard
</a>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

My Profile

</h4>

</div>

<div class="card-body">

<p><b>Name :</b> <?= $user['name']; ?></p>

<p><b>Email :</b> <?= $user['email']; ?></p>

<p><b>Role :</b> <?= $user['role']; ?></p>
<p><b>Modules ;</b> <?= $user['modules']; ?></p>

<?php 
if(
$_SESSION['role']=="Admin"
||
$_SESSION['role']=="Super Admin"
){ ?>

<a
href="change_password.php"
class="btn btn-warning">

Change Password

</a>
<?php } ?>

</div>

</div>

</div>

</body>

</html>