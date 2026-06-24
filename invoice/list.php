<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

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



$search = "";

if(!empty($_GET['search']))
{

$keyword =
mysqli_real_escape_string(
$conn,
$_GET['search']
);

$search =
" AND (

invoices.invoice_no LIKE '%$keyword%'

OR

customers.customer_name LIKE '%$keyword%'

)";

}

$q = mysqli_query(
$conn,

"SELECT invoices.*,
customers.customer_name

FROM invoices

LEFT JOIN customers
ON customers.id=invoices.customer_id

WHERE 1=1

$search

ORDER BY invoices.id DESC"

);


?>

<!DOCTYPE html>
<html>
<head>

<title>Invoice List</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

</head>

<body>

<div class="container mt-4">

<div class="d-flex justify-content-between">

<a
href="../dashboard.php"
class="btn btn-secondary">

← Dashboard

</a>

<a
href="create.php"
class="btn btn-success">

+ Create Invoice

</a>

</div>
<br>

<form method="GET" class="row mb-3">

<div class="col-md-5">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Invoice No. or Customer Name"
value="<?= $_GET['search'] ?? ''; ?>"
list="searchList">

<datalist id="searchList">

<?php

$searchData = mysqli_query(

$conn,

"SELECT

invoice_no

FROM invoices

UNION

SELECT

customer_name

FROM customers

ORDER BY 1 ASC"

);

while($s = mysqli_fetch_assoc($searchData))
{
?>

<option
value="<?= $s[array_key_first($s)]; ?>">

<?php } ?>

</datalist>

</div>

<div class="col-md-3">

<button
type="submit"
class="btn btn-primary w-100">

Search

</button>

</div>

<div class="col-md-2">

<a
href="list.php"
class="btn btn-secondary w-100">

Reset

</a>

</div>

<div class="col-md-2">

<a
href="export_invoice.php?search=<?= urlencode($_GET['search'] ?? ''); ?>"
class="btn btn-success w-100">

Export Excel

</a>

</div>

</form>

<hr>

<h2>Invoice List</h2>
<table class="table table-bordered">

<tr>

<th>Invoice No</th>

<th>Customer Name</th>

<th>Date</th>

<th>Taxable Amount</th>

<th>GST Amount</th>

<th>Total Amount</th>

<th style="text-align:center;">
Action
</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($q))
{

$gstAmount =

(float)$row['cgst_amount']

+

(float)$row['sgst_amount']

+

(float)$row['igst_amount'];

?>

<tr>

<td>

<?= $row['invoice_no']; ?>

</td>

<td>

<?= $row['customer_name']; ?>

</td>

<td>

<?= date(
'd-m-Y',
strtotime($row['invoice_date'])
); ?>

</td>

<td class="text-primary fw-bold">

₹ <?= indian_currency(
$row['taxable_value']
); ?>

</td>

<td class="text-success fw-bold">

₹ <?= indian_currency(
$gstAmount
); ?>

</td>

<td class="text-danger fw-bold">

₹ <?= indian_currency(
$row['grand_total']
); ?>

</td>

<td>

<a
href="view.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm">

View Invoice

</a>

&nbsp;

<a
href="edit_invoices.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Edit Invoice

</a>

&nbsp;

<a
href="delete_invoices.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to delete this invoice?');">

Delete Invoice

</a>

</td>

</tr>

<?php } ?>

<!-- <?php while($row=mysqli_fetch_assoc($q)){
    
    $paid =
mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT
IFNULL(SUM(amount),0)
total

FROM payments

WHERE invoice_id=".$row['id']

)

);

$paidAmount =
$paid['total'];

$balance =
$row['grand_total']
-
$paidAmount;

if($paidAmount <= 0)
{
$status = "UNPAID";
}
elseif($paidAmount < $row['grand_total'])
{
$status = "PARTIAL";
}
else
{
$status = "PAID";
}
    
    ?>

<tr>

<td><?= $row['invoice_no']; ?></td>

<td><?= $row['customer_name']; ?></td>

<td><?= $row['invoice_date']; ?></td>

<td>₹ <?= indian_currency($row['grand_total']); ?></td>


<td><?= indian_currency($paidAmount); ?></td>

<td><?= indian_currency($balance); ?></td>

<td class="status-col"><?= $status; ?></td>
<td>

<a
href="view.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm">
View Invoice
</a>&nbsp;&nbsp;&nbsp;

<a
href="edit_invoices.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Edit Invoice

</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a
href="delete_invoices.php?id=<?= $row['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Are you sure you want to delete this invoice?');">

Delete Invoice

</a>

<!-- <a
href="../uploads/invoices/<?= $row['pdf_file']; ?>"
target="_blank">

View PDF

</a> -->
</td>

</tr>

<?php } ?> -->

</table>

</div>

  <!-- <script>
  // Find all table cells with the class 'status-col'
  document.querySelectorAll('.status-col').forEach(cell => {
    const text = cell.textContent.trim().toLowerCase();
    
    // Apply colors based on the text inside the cell
    if (text === 'paid') {
      cell.style.backgroundColor = '#d4edda';
      cell.style.color = '#155724';
    } else if (text === 'unpaid') {
      cell.style.backgroundColor = '#f8d7da';
      cell.style.color = '#721c24';
    } else if (text === 'partial') {
      cell.style.backgroundColor = '#fff3cd';
      cell.style.color = '#856404';
    }
  });
</script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

</body>
</html>