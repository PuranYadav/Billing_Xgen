<?php

include "../auth.php";
include "../db.php";
include "../common_functions.php";

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

$where = " WHERE 1=1 ";

if(
isset($_GET['card_status'])
&&
$_GET['card_status'] != ''
)
{

$statusFilter =
mysqli_real_escape_string(
$conn,
$_GET['card_status']
);

$where .=
" AND invoices.status='$statusFilter' ";

}


if(!empty($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );

    $where .= "
    AND (
        invoices.invoice_no LIKE '%$search%'
        OR customers.customer_name LIKE '%$search%'
    )";
}

if(!empty($_GET['status']))
{
    $status = mysqli_real_escape_string(
        $conn,
        $_GET['status']
    );

    $where .= "
    AND invoices.status='$status'
    ";
}

if(
!empty($_GET['from_date'])
&&
!empty($_GET['to_date'])
)
{
    $from = $_GET['from_date'];
    $to   = $_GET['to_date'];

    $where .= "
    AND invoices.invoice_date
    BETWEEN '$from'
    AND '$to'
    ";
}

// $q = mysqli_query(

// $conn,

// "SELECT

// invoices.*,

// customers.customer_name,

// (
// IFNULL(invoices.cgst_amount,0)
// +
// IFNULL(invoices.sgst_amount,0)
// +
// IFNULL(invoices.igst_amount,0)
// )

// AS gst_amount

// FROM invoices

// LEFT JOIN customers
// ON customers.id=invoices.customer_id

// $where

// ORDER BY invoices.id DESC"

// );

$q = mysqli_query(

$conn,

"SELECT

invoices.*,

customers.customer_name,

IFNULL(
SUM(payments.amount),
0
) AS received_amount

FROM invoices

LEFT JOIN customers
ON customers.id = invoices.customer_id

LEFT JOIN payments
ON payments.invoice_id = invoices.id

$where

GROUP BY invoices.id

ORDER BY invoices.id DESC"

);

$totalInvoices = 0;
$activeInvoices = 0;
$inactiveInvoices = 0;
$totalValue = 0;

$countQ = mysqli_query(
$conn,

"SELECT
COUNT(*) total,
SUM(grand_total) total_value,
SUM(CASE WHEN status='Active' THEN 1 ELSE 0 END) active_count,
SUM(CASE WHEN status='Inactive' THEN 1 ELSE 0 END) inactive_count
FROM invoices"

);

$countData =
mysqli_fetch_assoc($countQ);

?>

<!DOCTYPE html>

<html>

<head>

<title>Invoice Report</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">


</head>

<body>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between mb-3">

<h3>
Invoice Report
</h3>

<div>

<a
href="../dashboard.php"
class="btn btn-secondary">

Dashboard

</a>

<a
href="javascript:history.back()"
class="btn btn-dark">

Back

</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<a
href="invoice_report.php"
class="text-decoration-none">

<div class="card bg-primary text-white">

<div class="card-body text-center">

<h6>

Total Invoices

</h6>

<h4>

<?= $countData['total']; ?>

</h4>

</div>

</div>

</a>

</div>


<div class="col-md-3">

<a
href="invoice_report.php?card_status=Active"
class="text-decoration-none">

<div class="card bg-success text-white">

<div class="card-body text-center">

<h6>

Active Invoices

</h6>

<h4>

<?= $countData['active_count']; ?>

</h4>

</div>

</div>

</a>

</div> 


<div class="col-md-3">

<a
href="invoice_report.php?card_status=Inactive"
class="text-decoration-none">

<div class="card bg-danger text-white">

<div class="card-body text-center">

<h6>

Inactive Invoices

</h6>

<h4>

<?= $countData['inactive_count']; ?>

</h4>

</div>

</div>

</a>

</div>


<div class="col-md-3">

<div class="card bg-warning">

<div class="card-body text-center">

<h6>Total Invoice Value</h6>

<h5>

₹ <?= indian_currency(
$countData['total_value'] ?? 0
); ?>

</h5>

</div>

</div>

</div>

</div>
<form method="GET" class="row mb-3 align-items-end"> 
    <div class="col-md-3 mb-2 mb-md-0"> 
        <label class="form-label font-weight-bold">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Invoice No / Customer Name" value="<?= $_GET['search'] ?? ''; ?>"> 
    </div> 
    
    <div class="col-md-2 mb-2 mb-md-0"> 
        <label class="form-label font-weight-bold">Status</label>
        <select name="status" class="form-control"> 
            <option value=""> All Status </option> 
            <option value="Active"> Active </option> 
            <option value="Inactive"> Inactive </option> 
        </select> 
    </div> 
    
    <div class="col-md-2 mb-2 mb-md-0"> 
        <label class="form-label font-weight-bold">From Date</label>
        <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? ''; ?>"> 
    </div> 
    
    <div class="col-md-2 mb-2 mb-md-0"> 
        <label class="form-label font-weight-bold">To Date</label>
        <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? ''; ?>"> 
    </div> 
    
    <div class="col-md-1 col-6"> 
        <button class="btn btn-primary w-100"> Search </button> 
    </div> 
    
    <div class="col-md-1 col-6"> 
        <a href="invoice_report.php" class="btn btn-secondary w-100"> Reset </a> 
    </div> 
     
    <div class="col-md-1">

<a
href="export_invoice_report.php?search=<?= urlencode($_GET['search'] ?? ''); ?>&status=<?= urlencode($_GET['status'] ?? ''); ?>&from_date=<?= urlencode($_GET['from_date'] ?? ''); ?>&to_date=<?= urlencode($_GET['to_date'] ?? ''); ?>"
class="btn btn-success w-100">

Export Excel

</a>

</div>

</form>



<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Invoice No</th>

<th>Date</th>

<th>Customer</th>

<th>Total Amount</th>

<th>Received Amount</th>

<th>Balance Amount</th>

<th>Payment Type</th>
<!-- 
<th>Taxable Amount</th>

<th>GST Amount</th>

<th>Total Amount</th> -->
<!-- 
<th> Payment Due Date </th> -->

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>


<?php

while($row=mysqli_fetch_assoc($q))
{

$paidAmount =
$row['received_amount'];

$balance =
$row['grand_total']
-
$paidAmount;

if($paidAmount <= 0)
{
    $paymentType =
    "<span class='badge bg-danger'>
    UNPAID
    </span>";
}
elseif($paidAmount < $row['grand_total'])
{
    $paymentType =
    "<span class='badge bg-warning text-dark'>
    PARTIAL
    </span>";
}
else
{
    $paymentType =
    "<span class='badge bg-success'>
    PAID
    </span>";
}

?>

<tr>

<td>
<?= $row['invoice_no']; ?>
</td>

<td>
<?= $row['invoice_date']; ?>
</td>

<td>
<?= $row['customer_name']; ?>
</td>

<td>
₹ <?= indian_currency($row['grand_total']); ?>
</td>

<td>
₹ <?= indian_currency($paidAmount); ?>
</td>

<td>
₹ <?= indian_currency($balance); ?>
</td>

<!-- <td class="text-primary fw-bold">
₹ <?= indian_currency($row['grand_total']); ?>
</td>

<td class="text-success fw-bold">
₹ <?= indian_currency($paidAmount); ?>
</td>

<td class="text-danger fw-bold">
₹ <?= indian_currency($balance); ?>
</td>

<td>
<?= $paymentType; ?>
</td> -->

<!-- <td>
<span class="badge bg-primary fs-6">
₹ <?= indian_currency($row['grand_total']); ?>
</span>
</td>

<td>
<span class="badge bg-success fs-6">
₹ <?= indian_currency($paidAmount); ?>
</span>
</td>

<td>
<span class="badge bg-danger fs-6">
₹ <?= indian_currency($balance); ?>
</span>
</td>

<td>
<?= $paymentType; ?>
</td> -->


<td>
<?= $paymentType; ?>
</td>
<!-- 
<td>

<?php

if($row['status'] == 'Inactive')
{

    echo "<span class='badge bg-success'>
    Paid
    </span>";

}
else
{

    $today = date('Y-m-d');

    $dueDate = $row['payment_due_date'];

    $daysLeft = floor(
    (strtotime($dueDate) - strtotime($today))
    / 86400
    );

    if($daysLeft < 0)
    {
        echo "<span class='badge bg-danger'>
        Overdue ".abs($daysLeft)." Days
        </span>";
    }
    elseif($daysLeft == 0)
    {
        echo "<span class='badge bg-warning text-dark'>
        Due Today
        </span>";
    }
    else
    {
        echo "<span class='badge bg-info'>
        ".$daysLeft." Days Left
        </span>";
    }

}

?>

</td> -->



<!-- <td>
<?php

if(
!empty($row['payment_due_date'])
&&
$row['payment_due_date'] != '0000-00-00'
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
    echo '-';
}

?>
</td> -->

<td>

<?php if($row['status']=='Active'){ ?>

<span class="badge bg-success">

Active

</span>

<?php } else { ?>

<span class="badge bg-danger">

Inactive

</span>

<?php } ?>

</td>

<td>

<a
href="view.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm">

View Invoice

</a>

<a
href="update_status.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit Status

</a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

</body>

</html>
