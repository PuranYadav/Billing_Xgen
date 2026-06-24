<?php

include "../auth.php";
include "../db.php";
include "role_check.php";

?>

<!DOCTYPE html>
<html>

<head>

<title>Add User</title>
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">


<!-- Option Js-->

</head>

<body>

<div class="container mt-4">

<div class="card">

<div class="card-header">

<h4>Add User</h4>

</div>

<div class="card-body">

<form method="POST" action="save.php">

<div class="mb-3">

<label>Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Role</label>

<select
name="role"
class="form-control">

<option>Admin</option>
<option>Accountant</option>

</select>

</div>


<!-- Bootstrap Select CSS -->

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<div class="mb-3">

<label>

Modules Access

</label>

<select

name="modules[]"

class="selectpicker form-control"

multiple

data-live-search="true"

data-actions-box="true"

title="Select Modules">

<option value="dashboard">
Dashboard
</option>

<option value="customers">
Customer Management
</option>

<option value="invoice">
Invoice Management
</option>

<option value="payments">
Payment Management
</option>

<option value="recieve_payments">
Recieve Payments
</option>

<option value="users">
User Management
</option>

<option value="revenue">
Total Revenue
</option>

<option value="outstanding">
Outstanding
</option>

</select>

</div>



<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="1">
Active
</option>

<option value="0">
Inactive
</option>

</select>

</div>

<button
class="btn btn-success">

Save User

</button>

</form>

</div>

</div>

</div>

<!-- Bootstrap Select JS -->
 
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function(){
    $('.selectpicker').selectpicker();
});
</script>
</body>

</html>