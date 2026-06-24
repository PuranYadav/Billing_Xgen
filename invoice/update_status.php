<?php

include "../auth.php";
include "../db.php";

if(
$_SESSION['role'] != 'Admin'
&&
$_SESSION['role'] != 'Super Admin'
&&
!in_array('invoice', $_SESSION['modules'])
)
{
    header("Location:../dashboard.php");
    exit;
}

$id = $_GET['id'];

if(empty($id))
{
    header("Location:invoice_report.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $id =
    mysqli_real_escape_string(
    $conn,
    $_POST['id']
    );

    $status =
    mysqli_real_escape_string(
    $conn,
    $_POST['status']
    );

    mysqli_query(

    $conn,

    "UPDATE invoices

    SET status='$status'

    WHERE id='$id'"

    );

    header(
    "Location:invoice_report.php?msg=updated"
    );

    exit;
}

$invoice =
mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT

id,
invoice_no,
invoice_date,
grand_total,
status

FROM invoices

WHERE id='$id'"

)

);

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Invoice Status</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-warning">

<h4 class="mb-0">

Edit Invoice Status

</h4>

</div>

<div class="card-body">

<form method="POST">

<input
type="hidden"
name="id"
value="<?= $invoice['id']; ?>">

<div class="mb-3">

<label class="form-label">

Invoice No

</label>

<input
type="text"
class="form-control"
value="<?= $invoice['invoice_no']; ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

Invoice Date

</label>

<input
type="text"
class="form-control"
value="<?= $invoice['invoice_date']; ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

Total Amount

</label>

<input
type="text"
class="form-control"
value="<?= $invoice['grand_total']; ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

Invoice Status

</label>

<select
name="status"
class="form-control">

<option
value="Active"
<?= $invoice['status']=="Active" ? "selected" : ""; ?>>

Active

</option>

<option
value="Inactive"
<?= $invoice['status']=="Inactive" ? "selected" : ""; ?>>

Inactive

</option>

</select>

</div>

<button
type="submit"
class="btn btn-success">

Update Status

</button>

<a
href="invoice_report.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>
