<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

  if(
$_SESSION['role']!='Admin'
&&
$_SESSION['role']!='Super Admin'
&&
!in_array('outstanding',$_SESSION['modules'])
)
{
   header("Location:../dashboard.php");
    exit;
}

$where = "";

if(!empty($_GET['year']))
{

    $year = (int)$_GET['year'];

    $where .= "

    AND YEAR(i.invoice_date) = '$year'

    ";

}

if(!empty($_GET['month']))
{

    $month = (int)$_GET['month'];

    $where .= "

    AND MONTH(i.invoice_date) = '$month'

    ";

}

$search = "";

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



$result = mysqli_query(

$conn,

"SELECT

c.customer_name,

i.invoice_no,

i.invoice_date,
i.payment_due_date,
i.status,

i.grand_total AS total_amount,

IFNULL(
SUM(p.amount),
0
) AS received_amount,

(
i.grand_total -
IFNULL(
SUM(p.amount),
0
)
) AS balance_amount

FROM invoices i

LEFT JOIN customers c
ON c.id = i.customer_id

LEFT JOIN payments p
ON p.invoice_id = i.id

WHERE 1=1

$where

$search

GROUP BY i.id

HAVING balance_amount > 0

ORDER BY i.invoice_date DESC"

);
?>

<!DOCTYPE html>
<html>

<head>

<title>Outstanding Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid mt-3">

<div class="card shadow-sm">

<div class="card-body d-flex justify-content-between align-items-center">

<div>

<h3 class="mb-0 text-primary">
Outstanding Report
</h3>

<small class="text-muted">
Pending Customer Payments
</small>

</div>

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

</div>

</div>

<br>
<form method="GET" class="row mb-3">

<div class="col-md-3">

<label>Customer</label>

<input
type="text"
name="search"
class="form-control"
placeholder="Customer Name"
value="<?= $_GET['search'] ?? ''; ?>">

</div>

<div class="col-md-2">

<label>Month</label>

<select
name="month"
class="form-control">

<option
value=""
<?= empty($_GET['month']) ? 'selected' : ''; ?>>

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
(isset($_GET['year']) ? $_GET['year'] : date('Y'))
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
class="btn btn-primary w-100">

Filter

</button>

</div>

<div class="col-md-2 d-flex align-items-end">

<a
href="list.php"
class="btn btn-secondary w-100">

Reset

</a>

</div>

<div class="col-md-1 d-flex align-items-end">

<a
href="export_outstanding.php?search=<?= urlencode($_GET['search'] ?? ''); ?>&month=<?= $_GET['month'] ?? ''; ?>&year=<?= $_GET['year'] ?? ''; ?>"
class="btn btn-success w-100">

Export Excel

</a>

</div>
</form>

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>Customer Name</th>

<th>Invoice No</th>

<th>Invoice Date</th>

<th>Due Date</th>

<th>Payment Status</th>

<th>Total Amount</th>

<th>Received Amount</th>

<th>Balance Amount</th>

</tr>

</thead>

<tbody>
<?php

$totalOutstanding = 0;

while($row = mysqli_fetch_assoc($result))
{

$totalOutstanding += $row['balance_amount'];

$today = date('Y-m-d');

if(
$row['balance_amount'] <= 0
||
$row['status'] == 'Inactive'
)
{
    $paymentStatus =
    "<span class='badge bg-success'>
    Paid
    </span>";
}
else
{

    if(
    !empty($row['payment_due_date'])
    &&
    $row['payment_due_date'] != '0000-00-00'
    )
    {

        $daysLeft =
        floor(
        (
        strtotime($row['payment_due_date'])
        -
        strtotime($today)
        )
        / 86400
        );

        if($daysLeft < 0)
        {
            $paymentStatus =
            "<span class='badge bg-danger'>
            Overdue ".abs($daysLeft)." Days
            </span>";
        }
        elseif($daysLeft == 0)
        {
            $paymentStatus =
            "<span class='badge bg-warning text-dark'>
            Due Today
            </span>";
        }
        elseif($daysLeft <= 7)
        {
            $paymentStatus =
            "<span class='badge bg-warning text-dark'>
            Due In ".$daysLeft." Days
            </span>";
        }
        else
        {
            $paymentStatus =
            "<span class='badge bg-info'>
            ".$daysLeft." Days Left
            </span>";
        }

    }
    else
    {
        $paymentStatus =
        "<span class='badge bg-secondary'>
        No Due Date
        </span>";
    }

}

?>

<tr>

<td>
<?= $row['customer_name']; ?>
</td>

<td>
<?= $row['invoice_no']; ?>
</td>

<td>
<?= date(
'd-m-Y',
strtotime($row['invoice_date'])
); ?>
</td>

<td>

<?php

if(
!empty($row['payment_due_date'])
&&
$row['payment_due_date']!='0000-00-00'
)
{
    echo date(
    'd-m-Y',
    strtotime(
    $row['payment_due_date']
    )
    );
}
else
{
    echo "-";
}

?>

</td>

<td>
<?= $paymentStatus; ?>
</td>

<td>
₹ <?= indian_currency($row['total_amount']); ?>
</td>

<td>
₹ <?= indian_currency($row['received_amount']); ?>
</td>

<td class="text-danger fw-bold">
₹ <?= indian_currency($row['balance_amount']); ?>
</td>

</tr>

<?php } ?>

</tbody>

<tfoot>

<tr>

<th colspan="7" class="text-end">

Total Outstanding

</th>

<th class="text-danger">

₹ <?= indian_currency($totalOutstanding); ?>

</th>

</tr>

</tfoot>

</table>



</div>

</body>

</html>