<?php

session_start();

include "db.php";


if(isset($_POST['login']))
{
    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = (
        $_POST['password']
    );

    
    $query = mysqli_query(

$conn,

"SELECT *
FROM users
WHERE email='$email'
AND status=1"

);

if(mysqli_num_rows($query)>0)
{

$user =
mysqli_fetch_assoc($query);

if(
password_verify(
$password,
$user['password']
)
)
{

$_SESSION['user_id'] =
$user['id'];

$_SESSION['name'] =
$user['name'];

$_SESSION['role'] =
$user['role'];

$_SESSION['modules'] = [];

if(
$user['role'] == 'Super Admin'
||
$user['role'] == 'Admin'
)
{
    $_SESSION['modules'] = [

        'customers',
        'invoice',
        'payments',
        'users',
        'revenue',
        'outstanding'

    ];
}
else
{
    $_SESSION['modules'] =
    explode(
        ',',
        $user['modules']
    );
}

header("Location:dashboard.php");
exit;

}
else
{
$error =
"Invalid Password";
}

}
else
{
$error =
"User Not Found";
}

}
?>



<!DOCTYPE html>
<html>
<head>

<title>XGEN Billing Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-4">
<div class="card">

    <div class="card-body text-center">

        <img
        src="/Billing%20Xgen/assets/images/final logo.png"
        alt="XGEN Logo"
        style="width:180px;height:auto;">

        <h3 class="mt-3">
            XGEN Billing Login
        </h3>

    </div>



<div class="card-body">

<?php if(isset($error)){ ?>

<div class="alert alert-danger">
<?= $error ?>
</div>

<?php } ?>

<form method="POST">

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

<button
type="submit"
name="login"
class="btn btn-primary w-100">

Login

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>