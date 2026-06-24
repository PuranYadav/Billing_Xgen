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


$q = mysqli_query(

$conn,

"SELECT
payments.*,

invoices.invoice_no,

customers.customer_name

FROM payments

LEFT JOIN invoices
ON invoices.id = payments.invoice_id

LEFT JOIN customers
ON customers.id = invoices.customer_id

ORDER BY payments.id DESC"

);

?>

<!DOCTYPE html>
<html>

<head>

<title>Payment Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    border:none;
    border-radius:12px;
}

.table th{
    background:#212529;
    color:#fff;
    text-align:center;
    vertical-align:middle;
}

.table td{
    vertical-align:middle;
}

.amount{
    font-weight:bold;
    color:green;
}

</style>

</head>

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3 class="mb-0">
Payment Management
</h3>

<div>

<a
href="../dashboard.php"
class="btn btn-secondary">

← Dashboard

</a>

<a
href="add.php"
class="btn btn-success">

+ Receive Payment

</a>

</div>

</div>

<div class="card shadow">

<div class="card-body">

<div class="table-responsive">



<table class="table table-bordered table-hover">

<thead>

<tr>

<th width="6%">ID</th>

<th width="15%">Invoice No</th>

<th width="22%">Customer Name</th>

<th width="12%">Payment Date</th>

<th width="12%">Amount</th>

<th width="12%">Mode</th>

<th width="12%">Reference</th>

<th width="19%">Remarks</th>

</tr>

</thead>

<tbody>

<?php

$sr = 1;

while($r=mysqli_fetch_assoc($q))
{

?>

<tr>

<td class="text-center">

<?= $sr++; ?>

</td>

<td>

<?= $r['invoice_no']; ?>

</td>

<td>

<?= $r['customer_name']; ?>

</td>

<td class="text-center">

<?= date(
'd-M-Y',
strtotime($r['payment_date'])
); ?>

</td>

<td class="text-end amount">

₹ <?= number_format(
$r['amount'],
2
); ?>

</td>

<td class="text-center">

<?= $r['payment_mode']; ?>

</td>

<td>

<?= $r['reference_no']; ?>

</td>

<td>

<?= $r['remarks']; ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>

</html>



