<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

$id = $_GET['id'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM customers WHERE id='$id'"
);

$data = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $customer_name = $_POST['customer_name'];
    $gst_number = $_POST['gst_number'];
    $billing_address = $_POST['billing_address'];
    $state_name = $_POST['state_name'];
    $state_code = $_POST['state_code'];
    $po_number = $_POST['po_number'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $created_at = $_POST['created_at'];
    mysqli_query(
        $conn,
        "UPDATE customers SET

        customer_name='$customer_name',
        gst_number='$gst_number',
        billing_address='$billing_address',
        state_name='$state_name',
        state_code='$state_code',
        po_number='$po_number',
        contact_person='$contact_person',
        email='$email',
        phone='$phone',
       created_at='$created_at'
        WHERE id='$id'"
    );

    header("Location:list.php");
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>

<title>Edit Customer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h2>Edit Customer</h2>

<form method="POST">

<div class="row">

<div class="col-md-6">
<label>Customer Name</label>
<input
type="text"
name="customer_name"
class="form-control"
value="<?= $data['customer_name']; ?>"
required>
</div>

<div class="col-md-6">
<label>GST Number</label>
<input
type="text"
name="gst_number"
class="form-control"
value="<?= $data['gst_number']; ?>">
</div>

</div>

<br>

<label>Billing Address</label>

<textarea
name="billing_address"
class="form-control"><?= $data['billing_address']; ?></textarea>

<br>

<div class="row">

<div class="col-md-3">
<label>State</label>
<input
type="text"
name="state_name"
class="form-control"
value="<?= $data['state_name']; ?>">
</div>

<div class="col-md-3">
<label>State Code</label>
<input
type="text"
name="state_code"
class="form-control"
value="<?= $data['state_code']; ?>">
</div>

<div class="col-md-3">
<label>PO Number</label>
<input
type="text"
name="po_number"
class="form-control"
value="<?= $data['po_number']; ?>">
</div>

<div class="col-md-3">
<label>Contact Person</label>
<input
type="text"
name="contact_person"
class="form-control"
value="<?= $data['contact_person']; ?>">
</div>

</div>

<br>

<div class="row">

<div class="col-md-3">
<label>Email</label>
<input
type="email"
name="email"
class="form-control"
value="<?= $data['email']; ?>">
</div>

<div class="col-md-3">
<label>Phone</label>
<input
type="text"
name="phone"
class="form-control"
value="<?= $data['phone']; ?>">
</div>

<div class="mb-3">

<label class="form-label">
Customer Created Date
</label>

<input
type="date"
name="created_at"
value="<?= $data['created_at']; ?>"
class="form-control">

</div>
</div>

<br>

<button
type="submit"
name="update"
class="btn btn-primary">

Update Customer

</button>

<a
href="list.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</body>
</html>