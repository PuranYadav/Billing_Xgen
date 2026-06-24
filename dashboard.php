<?php

include "auth.php";
include "db.php";

include "common_functions.php";


// $whereInvoice = "";
// $whereCustomer = "";

// if(
// !empty($_GET['from_date'])
// &&
// !empty($_GET['to_date'])
// )
// {

// $from =
// mysqli_real_escape_string(
// $conn,
// $_GET['from_date']
// );

// $to =
// mysqli_real_escape_string(
// $conn,
// $_GET['to_date']
// );

// $whereInvoice =
// " WHERE invoice_date
// BETWEEN '$from'
// AND '$to' ";

// $whereCustomer =
// " WHERE DATE(created_at)
// BETWEEN '$from'
// AND '$to' ";

// }
/* Total Users */

$totalUsers =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM users"
));

/* Total Customers */

$totalCustomers =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM customers
$whereCustomer"
));

/* Total Invoices */

$totalInvoices =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM invoices
$whereInvoice"
));


$totalPayments =
mysqli_fetch_assoc(
mysqli_query(
$conn,

"SELECT
IFNULL(SUM(amount),0)
total

FROM payments"

)
);
/* Total Revenue */

$totalRevenue =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT SUM(grand_total) total FROM invoices
$whereInvoice"
));

 $receivedAmount =
mysqli_fetch_assoc(
mysqli_query(
$conn,

"SELECT
IFNULL(SUM(amount),0)
total

FROM payments"

)
);

$outstanding =
mysqli_fetch_assoc(
mysqli_query(
$conn,

"SELECT
IFNULL(
SUM(i.grand_total),0
) -

IFNULL(
(
SELECT SUM(amount)
FROM payments
),
0
)

AS total

FROM invoices i"

)
);



?>



<!-- <?php

include "auth.php";
include "db.php";

$totalUsers =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM users"
));

?> -->


<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .card{
    border:none;
    border-radius:12px;
    transition:0.3s;
    min-height:120px;
}

.card:hover{
    transform:translateY(-5px);
}

.card-body{
    padding:20px;
}

.card h2{
    margin:0;
    font-size:28px;
    font-weight:700;
}

.card h5,
.card h6{
    margin-bottom:10px;
}
</style>

</head>

<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            XGEN LOGISTICS
        </span>

        <div class="ms-auto pe-4">

            <img
            src="assets/images/final logo.png"
            alt="XGEN Logo"
            style="
                height:80px;
                width:auto;
                object-fit:contain;
            ">

        </div>

    </div>

</nav>

<nav class="navbar navbar-dark bg-dark">

<div class="container-fluid">

<span class="navbar-brand">
 Dashboard
</span>


<?php if(
$_SESSION['role']=='Admin'
||
$_SESSION['role']=='Super Admin'
||
in_array('customers',$_SESSION['modules'])
){ ?>


<a
href="customers/list.php"
class="btn btn-success">

Customer Management

</a>

<?php } ?>
<!-- <a
href="customers/list.php"
class="btn btn-success">

Customer Management

</a> -->

<!-- <a
href="invoice/list.php"
class="btn btn-warning">

Invoice Management

</a> -->
<?php if(
$_SESSION['role']=='Admin'
||
$_SESSION['role']=='Super Admin'
||
in_array('invoice',$_SESSION['modules'])
){ ?>


<a
href="invoice/list.php"
class="btn btn-warning">

Invoice Management

</a>

<?php } ?>

<?php if(
$_SESSION['role']=='Admin'
||
$_SESSION['role']=='Super Admin'
||
in_array('users',$_SESSION['modules'])
){ ?>


<a
href="users/list.php"
class="btn btn-primary">

User Management

</a>

<?php } ?>

<!-- <a
href="users/list.php"
class="btn btn-primary">

User Management

</a> -->



<a
href="profile.php"
class="btn btn-info">

Profile

</a>

<a
href="logout.php"
class="btn btn-danger">

Logout

</a>

</div>

</nav>

<br><br>
<!-- <div class="row g-4">

<div class="col-md-3">

<div class="card bg-primary text-white shadow">

<div class="card-body text-center">

<h5>Total Users</h5>

<h2>

<?= $totalUsers['total']; ?>

</h2>

</div>

</div>

</div> -->

<!-- <form method="GET" class="row mb-4">

    <div class="col-md-3">
        <label>From Date</label>
        <input
        type="date"
        name="from_date"
        value="<?= $_GET['from_date'] ?? ''; ?>"
        class="form-control">
    </div>

    <div class="col-md-3">
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
        class="btn btn-primary w-100">

            Filter

        </button>
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <a
        href="dashboard.php"
        class="btn btn-secondary w-100">

            Reset

        </a>
    </div>

</form> -->

<div class="row g-3">

    <div class="col-md-4 mb-3">
        <a href="customers/list.php" class="text-decoration-none">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h6>Total Customers</h6>
                    <h2><?= $totalCustomers['total']; ?></h2>
                </div>
            </div>
        </a>
    </div>

    
    <div class="col-md-4 mb-3">
        <a href="invoice/invoice_report.php" class="text-decoration-none">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body text-center">
                    <h6>Total Invoices</h6>
                    <h2><?= $totalInvoices['total']; ?></h2>
                </div>
            </div>
        </a>
    </div>

        <?php

        if(
        $_SESSION['role']=='Admin'
        ||
        $_SESSION['role']=='Super Admin'
        ||
        in_array(
        'revenue',
        $_SESSION['modules']
        )
        )
        {
        ?>

<!-- Revenue Card -->


    <div class="col-md-4 mb-3">
        
        <a href="revenue/list.php" class="text-decoration-none">
            <div class="card bg-danger text-white shadow">
                <div class="card-body text-center">
                    <h6>Total Revenue</h6>
                    <h5>₹ <?= indian_currency($totalRevenue['total'] ?? 0); ?></h5>
                </div>
            </div>
        </a>
    </div>
      
    <?php } ?>

        <div class="col-md-4 mb-3">

        <a href="payments/add.php"
        class="text-decoration-none">

        <div class="card bg-secondary text-white shadow">

        <div class="card-body text-center">

        <h6>Payment Management</h6>

        <h5>

        Receive Payment

        </h5>

        </div>

        </div>

        </a>

        </div>

   <div class="col-md-4 mb-3">

            <a href="payments/list.php" class="text-decoration-none">

            <div class="card bg-success text-white shadow">

            <div class="card-body text-center">

            <h6>Received Amount</h6>

            <h5>

            ₹ <?= indian_currency($receivedAmount['total']); ?>

            </h5>

            </div>

            </div>

            </a>

            </div>
          
    
         <?php

            if(
            in_array(
            'outstanding',
            $_SESSION['modules']
            )
            ||
            $_SESSION['role']=='Admin'
            ||
            $_SESSION['role']=='Super Admin'
            )
            {

            ?>


   <div class="col-md-4 mb-3">
         <a href="outstanding/list.php" class="text-decoration-none">
        <div class="card bg-warning shadow">

        <div class="card-body text-center">

        <h6>Outstanding</h6>

        <h5>

        ₹ <?= indian_currency($outstanding['total']); ?>

        </h5>

        </div>

        </div>
        </a>
        </div>
     
        <?php }?>

</div>



<!-- 
<div class="container mt-4">

<div class="row">

<div class="col-md-3">

<div class="card bg-primary text-white">

<div class="card-body">

<h5>Total Users</h5>

<h2>
<?= $totalUsers['total']; ?>
</h2>

</div>

</div>

</div>

</div>

<br>

<h4>
Welcome,
<?= $_SESSION['name']; ?>
</h4>

<p>
Role :
<?= $_SESSION['role']; ?>
</p> -->

</div>

</body>
</html>