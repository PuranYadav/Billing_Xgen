<?php

include "../auth.php";
include "../db.php";
include "role_check.php";

$id = $_GET['id'];

$user =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM users WHERE id='$id'"
)
);


?>
<?php

$userModules = [];

if(!empty($user['modules']))
{
    $userModules =
    explode(
        ',',
        $user['modules']
    );
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    border:none;
    border-radius:12px;
}

</style>

</head>

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">

<h3>Edit User</h3>

<div>

<a
href="list.php"
class="btn btn-secondary">

← Back

</a>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-warning">

<h5 class="mb-0">

Update User Details

</h5>

</div>

<div class="card-body">

<form
method="POST"
action="update.php?id=<?= $id; ?>">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Name

</label>

<input
type="text"
name="name"
value="<?= $user['name']; ?>"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
name="email"
value="<?= $user['email']; ?>"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Role

</label>

<select
name="role"
class="form-control">

<option
value="Admin"
<?= $user['role']=="Admin" ? "selected" : ""; ?>>

Admin

</option>

<option
value="Accountant"
<?= $user['role']=="Accountant" ? "selected" : ""; ?>>

Accountant

</option>

</select>

</div>


<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<div class="mb-3">

<label>

Modules Access

</label>
<?php

$userModules = [];

if(!empty($user['modules']))
{
    $userModules = explode(',', $user['modules']);
}

?>

<select

name="modules[]"

class="selectpicker"

multiple

data-actions-box="true"

title="Select Modules">

<option value="dashboard"
<?php if(in_array('dashboard',$userModules)) echo 'selected'; ?>>
Dashboard
</option>

<option value="customers"
<?php if(in_array('customers',$userModules)) echo 'selected'; ?>>
Customer Management
</option>

<option value="invoice"
<?php if(in_array('invoice',$userModules)) echo 'selected'; ?>>
Invoice Management
</option>

<option value="payments"
<?php if(in_array('payments',$userModules)) echo 'selected'; ?>>
Payment Management
</option>

<option value="receive_payments"
<?php if(in_array('receive_payments',$userModules)) echo 'selected'; ?>>
Receive Payments
</option>

<option value="users"
<?php if(in_array('users',$userModules)) echo 'selected'; ?>>
User Management
</option>

<option value="revenue">
    <?php if(in_array('revenue',$userModules)) echo 'selected'; ?>
Total Revenue
</option>

<option value="outstanding"
<?php if(in_array('outstanding',$userModules)) echo 'selected'; ?>>
Outstanding
</option>

</select>

</div>

 

<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<select
name="status"
class="form-control">

<option
value="1"
<?= $user['status']==1 ? "selected" : ""; ?>>

Active

</option>

<option
value="0"
<?= $user['status']==0 ? "selected" : ""; ?>>

Inactive

</option>

</select>

</div>

</div>

<button
type="submit"
class="btn btn-success">

Update User

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
