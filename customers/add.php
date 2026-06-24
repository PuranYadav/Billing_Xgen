<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

if(isset($_POST['save']))
{

$customer_name=$_POST['customer_name'];
$gst=$_POST['gst_number'];
$address=$_POST['billing_address'];
$state=$_POST['state_name'];
$state_code=$_POST['state_code'];
$po=$_POST['po_number'];
$contact=$_POST['contact_person'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$created_at=$_POST['date'];
mysqli_query(
$conn,

"INSERT INTO customers(

customer_name,
gst_number,
billing_address,
state_name,
state_code,
po_number,
contact_person,
email,
phone,
created_at
)

VALUES(

'$customer_name',
'$gst',
'$address',
'$state',
'$state_code',
'$po',
'$contact',
'$email',
'$phone',
'$date'
)"
);


header("Location:list.php");
exit;
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Add Customer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h2>Add Customer</h2>

<form method="POST">

<div class="row">

<div class="col-md-6">

<label>Customer Name</label>

<input
type="text"
name="customer_name"
class="form-control"
required>

</div>

<div class="col-md-6">

<label>GST Number</label>

<input
type="text"
name="gst_number"
class="form-control">

</div>

</div>

<br>

<label>Billing Address</label>

<textarea
name="billing_address"
class="form-control">
</textarea>

<br>

<div class="row">

<div class="col-md-3">

<label>State</label>

<input
type="text"
name="state_name"
class="form-control">

</div>

<!-- <div class="col-md-3">
<label>Pincode</label>
<input
type="text"
name="pincode"
class="form-control">
</div> -->

<div class="col-md-3">

<label>State Code</label>

<input
type="text"
name="state_code"
class="form-control">

</div>

<div class="col-md-3">

<label>PO Number</label>

<input
type="text"
name="po_number"
class="form-control">

</div>
<div class="col-md-3">

<label>Contact Person</label>

<input
type="text"
name="contact_person"
class="form-control">

</div>
</div>

<br>

<div class="row">
   


<div class="col-md-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control">

</div>

<div class="col-md-3">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control">

</div>
<div class="mb-3">

<label class="form-label">
Customer Created Date
</label>

<input
type="date"
name="created_at"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>
</div>

<br>

<button
type="submit"
name="save"
class="btn btn-success">

Save Customer

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