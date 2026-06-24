<?php

include "../auth.php";
include "../db.php";
include "../common_functions.php";

if(
$_SESSION['role'] != 'Admin'
&&
$_SESSION['role'] != 'Super Admin'
&&
!in_array('payments', $_SESSION['modules'])
)
{
    header("Location:../dashboard.php");
    exit;
}

$invoices = mysqli_query(
$conn,

"SELECT invoices.*,
customers.customer_name

FROM invoices

LEFT JOIN customers
ON customers.id=invoices.customer_id

ORDER BY invoices.id DESC"

);

?>

<!DOCTYPE html>
<html>

<head>
    <br><br>
<div class="ms-5">
  <a href="../dashboard.php" class="btn btn-secondary">
    ← Dashboard
  </a>
</div>


<title>Receive Payment</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h3>Receive Payment</h3>

<form method="POST" action="save.php">

<div class="card">

<div class="card-body">

<div class="mb-3">

<label>Invoice</label>

<select
name="invoice_id"
class="form-control"
required>

<option value="">
Select Invoice
</option>

<?php while($r=mysqli_fetch_assoc($invoices)){ ?>

<option value="<?= $r['id']; ?>">

<?= $r['invoice_no']; ?>

-
<?= $r['customer_name']; ?>

(₹ <?= indian_currency($r['grand_total']); ?>)

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Payment Date</label>

<input
type="date"
name="payment_date"
value="<?= date('Y-m-d'); ?>"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Amount Received</label>

<input
type="number"
step="0.01"
name="amount"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Payment Mode</label>

<select
name="payment_mode"
class="form-control">

<option>Cash</option>
<option>Cheque</option>
<option>NEFT</option>
<option>RTGS</option>
<option>UPI</option>

</select>

</div>

<div class="mb-3">

<label>Reference No</label>

<input
type="text"
name="reference_no"
class="form-control">

</div>

<div class="mb-3">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"></textarea>

</div>

<button
class="btn btn-success">

Save Payment

</button>

</div>

</div>

</form>

</div>

</body>

</html>