<?php

include "auth.php";
include "db.php";

$msg = "";

if(isset($_POST['change']))
{

    $oldPassword = $_POST['old_password'];

     $newPassword =password_hash($_POST['new_password'],
      PASSWORD_DEFAULT);

    $userId = $_SESSION['user_id'];

    $user = mysqli_fetch_assoc(

    mysqli_query(

    $conn,

    "SELECT *
    FROM users
    WHERE id='$userId'"

    )

    );

            if(
            password_verify(
            $oldPassword,
            $user['password']
            )
            )
    {

        mysqli_query(

        $conn,

        "UPDATE users

        SET password='$newPassword'

        WHERE id='$userId'"

        );

        $msg = "Password Changed Successfully";

    }
    else
    {

        $msg = "Old Password Incorrect";

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Change Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<a href="profile.php" class="btn btn-secondary mb-3">
← Back
</a>

<div class="card shadow">

<div class="card-header bg-warning">

<h4 class="mb-0">

Change Password

</h4>

</div>

<div class="card-body">

<?php if($msg!=""){ ?>

<div class="alert alert-info">

<?= $msg; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Old Password</label>

<input
type="password"
name="old_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input
type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<button
type="submit"
name="change"
class="btn btn-success">

Update Password

</button>

</form>

</div>

</div>

</div>

</body>

</html>