<?php

include "../auth.php";

include "../db.php";

include "role_check.php";


$q = mysqli_query(
$conn,
"SELECT * FROM users ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>User Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">

<h3>User Management</h3>

<div>

<a href="../dashboard.php"
class="btn btn-secondary">

Dashboard

</a>

<a href="add.php"
class="btn btn-success">

Add User

</a>

</div>

</div>

<table class="table table-bordered table-striped">

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Modules/Permissions</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php while($row=mysqli_fetch_assoc($q)){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= $row['name']; ?></td>

<td><?= $row['email']; ?></td>

<td><?= $row['role']; ?></td>

<td><?= $row['modules']; ?></td>

<td>

<?=
$row['status']==1
?
'Active'
:
'Inactive';
?>

</td>

<td>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete User?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>

</html>