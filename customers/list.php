<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

if(
$_SESSION['role'] != 'Admin'
&&
$_SESSION['role'] != 'Super Admin'
&&
!in_array('customers', $_SESSION['modules'])
)
{
    header("Location:../dashboard.php");
    exit;
}

$where = "WHERE 1=1";

if(
!empty($_GET['month'])
&&
!empty($_GET['year'])
)
{

    $month = (int)$_GET['month'];
    $year  = (int)$_GET['year'];

    $where .= "

    AND MONTH(created_at)='$month'

    AND YEAR(created_at)='$year'

    ";

}

$sql = "

SELECT *

FROM customers

$where

ORDER BY id DESC

";

$result = mysqli_query(
$conn,
$sql
);
?>

<!DOCTYPE html>
<html>
<head>

<title>Customers</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<div class="card mb-3">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<a
href="../dashboard.php"
class="btn btn-outline-dark">

← Back To Dashboard

</a>

</div>

<div>

<a
href="add.php"
class="btn btn-success">

+ Add Customer

</a>

</div>

</div>

</div>
</div>

<form method="GET" class="row mb-3">

<div class="col-md-3">

<select name="month" class="form-control">

<option value="">All Months</option>

<?php
for($m=1;$m<=12;$m++)
{
?>
<option
value="<?= $m; ?>"
<?= (isset($_GET['month']) && $_GET['month']==$m) ? 'selected' : ''; ?>>

<?= date('F',mktime(0,0,0,$m,1)); ?>

</option>
<?php } ?>

</select>

</div>

<div class="col-md-3">

<select name="year" class="form-control">

<option value="">All Years</option>

<?php
for($y=date('Y');$y>=2020;$y--)
{
?>
<option
value="<?= $y; ?>"
<?= (isset($_GET['year']) && $_GET['year']==$y) ? 'selected' : ''; ?>>

<?= $y; ?>

</option>
<?php } ?>

</select>

</div>

<div class="col-md-2">

<button
type="submit"
class="btn btn-primary">

Filter

</button>

</div>

<div class="col-md-2">

<a
href="list.php"
class="btn btn-secondary">

Reset

</a>

</div>

</form>


<h2 class="mb-3">
Customer Management
</h2>
<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Created Date</th>
<th>Customer Name</th>
<th>GST No.</th>
<th>Phone No.</th>
<th>Email-Id</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['id']; ?></td>
<td><?= $row['created_at']; ?></td>
<td><?= $row['customer_name']; ?></td>

<td><?= $row['gst_number']; ?></td>

<td><?= $row['phone']; ?></td>

<td><?= $row['email']; ?></td>

<td>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete Customer?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>