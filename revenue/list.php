<?php

session_start();

include "../auth.php";
include "../db.php";
include "../common_functions.php";

if(
$_SESSION['role']!='Admin'
&&
$_SESSION['role']!='Super Admin'
&&
!in_array('revenue',$_SESSION['modules'])
)
{
    include "../permission_denied.php";
    exit;
}
$where = "";
$search = "";

if(
!empty($_GET['from_date'])
&&
!empty($_GET['to_date'])
)
{
    $from = $_GET['from_date'];
    $to   = $_GET['to_date'];

    $where .= "

    AND i.invoice_date
    BETWEEN '$from'
    AND '$to'

    ";
}

if(!empty($_GET['search']))
{
    $keyword =
    mysqli_real_escape_string(
    $conn,
    $_GET['search']
    );

    $search .= "

    AND c.customer_name
    LIKE '%$keyword%'

    ";
}

$query = mysqli_query(

$conn,

"SELECT

c.customer_name,

i.invoice_no,

i.invoice_date,

i.grand_total,

IFNULL(
SUM(p.amount),
0
) received_amount,

(
i.grand_total -
IFNULL(
SUM(p.amount),
0
)
) balance_amount

FROM invoices i

LEFT JOIN customers c
ON c.id=i.customer_id

LEFT JOIN payments p
ON p.invoice_id=i.id

WHERE 1=1

$where

$search

GROUP BY i.id

ORDER BY i.invoice_date DESC"

);

$totalRevenue  = 0;
$totalReceived = 0;
$totalBalance  = 0;

?>

<!DOCTYPE html>

<html>

<head>

<title>Revenue Report</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

.card{
border:none;
border-radius:12px;
}

.table th{
background:#343a40;
color:white;
}

</style>

</head>

<body>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between mb-3">

<h3 class="text-primary">

Revenue Report

</h3>

<div>

<a
href="../dashboard.php"
class="btn btn-primary">

Dashboard

</a>

<a
href="javascript:history.back()"
class="btn btn-secondary">

Back

</a>

</div>

</div>

<form method="GET" class="row mb-4">

<div class="col-md-3">

<label>Customer</label>

<input
type="text"
name="search"
list="customerList"
value="<?= $_GET['search'] ?? ''; ?>"
placeholder="Search Customer"
class="form-control">

<datalist id="customerList">

<?php

$customers =
mysqli_query(
$conn,
"SELECT customer_name
FROM customers
ORDER BY customer_name"
);

while($c=mysqli_fetch_assoc($customers))
{
?>

<option
value="<?= $c['customer_name']; ?>">

<?php } ?>

</datalist>

</div>

<div class="col-md-2">

<label>From Date</label>

<input
type="date"
name="from_date"
value="<?= $_GET['from_date'] ?? ''; ?>"
class="form-control">

</div>

<div class="col-md-2">

<label>To Date</label>

<input
type="date"
name="to_date"
value="<?= $_GET['to_date'] ?? ''; ?>"
class="form-control">

</div>

<div class="col-md-2 d-flex align-items-end">

<button
type="submit"
class="btn btn-success w-100">

Filter

</button>

</div>

<div class="col-md-1 d-flex align-items-end">

<a
href="list.php"
class="btn btn-danger w-100">

Reset

</a>

</div>

<div class="col-md-2 d-flex align-items-end">

<a
href="export_revenue.php?search=<?= urlencode($_GET['search'] ?? ''); ?>&from_date=<?= $_GET['from_date'] ?? ''; ?>&to_date=<?= $_GET['to_date'] ?? ''; ?>"
class="btn btn-primary w-100">

Export Excel

</a>

</div>

</form>

<!-- Month wise  -->
<!-- <form method="GET" class="row mb-4">

<div class="col-md-3">

<label>Customer</label>

<input
type="text"
name="search"
list="customerList"
value="<?= $_GET['search'] ?? ''; ?>"
placeholder="Customer Name"
class="form-control">

<datalist id="customerList">

<?php

$cust =
mysqli_query(
$conn,
"SELECT customer_name
FROM customers
ORDER BY customer_name"
);

while($c=mysqli_fetch_assoc($cust))
{
?>

<option
value="<?= $c['customer_name']; ?>">

<?php } ?>

</datalist>

</div>

<div class="col-md-2">

<label>Month</label>

<select
name="month"
class="form-control">

<option value="">
All Months
</option>

<?php

for($m=1;$m<=12;$m++)
{
?>

<option
value="<?= $m; ?>"
<?= (($_GET['month'] ?? '') == $m)
? 'selected'
: ''; ?>>

<?= date('F',mktime(0,0,0,$m,1)); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-2">

<label>Year</label>

<select
name="year"
class="form-control">

<?php

for(
$y=date('Y');
$y>=2020;
$y--
)
{
?>

<option
value="<?= $y; ?>"
<?= (
($_GET['year'] ?? date('Y'))
== $y
)
? 'selected'
: ''; ?>>

<?= $y; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-2 d-flex align-items-end">

<button
type="submit"
class="btn btn-success w-100">

Filter

</button>

</div>

<div class="col-md-1 d-flex align-items-end">

<a
href="list.php"
class="btn btn-danger w-100">

Reset

</a>

</div>

<div class="col-md-2 d-flex align-items-end">

<a
href="export_revenue.php?search=<?= urlencode($_GET['search'] ?? ''); ?>&month=<?= $_GET['month'] ?? ''; ?>&year=<?= $_GET['year'] ?? date('Y'); ?>"
class="btn btn-primary w-100">

Export

</a>

</div>

</form> -->

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Customer</th>

<th>Invoice No</th>

<th>Invoice Date</th>

<th>Total Amount</th>

<th>Received Amount</th>

<th>Balance Amount</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($query))
{

$totalRevenue += $row['grand_total'];

$totalReceived += $row['received_amount'];

$totalBalance += $row['balance_amount'];

?>

<tr>

<td>
<?= $row['customer_name']; ?>
</td>

<td>
<?= $row['invoice_no']; ?>
</td>

<td>
<?= date('d-m-Y',
strtotime($row['invoice_date'])
);

?>

</td>

<td>
₹ <?= indian_currency($row['grand_total']); ?>
</td>

<td class="text-success fw-bold">
₹ <?= indian_currency($row['received_amount']); ?>
</td>

<td class="text-danger fw-bold">
₹ <?= indian_currency($row['balance_amount']); ?>
</td>

</tr>

<?php } ?>

</tbody>

</table>

<div class="row mt-4">

<div class="col-md-4">

<div class="card bg-primary text-white shadow">

<div class="card-body text-center">

<h6>

Total Revenue

</h6>

<h4>

₹ <?= indian_currency($totalRevenue); ?>

</h4>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-success text-white shadow">

<div class="card-body text-center">

<h6>

Received Amount

</h6>

<h4>

₹ <?= indian_currency($totalReceived); ?>

</h4>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card bg-danger text-white shadow">

<div class="card-body text-center">

<h6>

Outstanding Amount

</h6>

<h4>

₹ <?= indian_currency($totalBalance); ?>

</h4>

</div>

</div>

</div>

</div>

</div>

</body>

</html>
