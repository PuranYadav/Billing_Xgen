<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include "../auth.php";
include "../db.php";

$searchWhere = "";
$statusWhere = "";
$dateWhere = "";

$fileName = "Invoice_Report";

/* Search Filter */

if(!empty($_GET['search']))
{
    $keyword =
    mysqli_real_escape_string(
    $conn,
    $_GET['search']
    );

    $searchWhere = "

    AND (

        invoices.invoice_no LIKE '%$keyword%'

        OR

        customers.customer_name LIKE '%$keyword%'

    )

    ";

    $fileName =
    str_replace(
    ' ',
    '_',
    trim($_GET['search'])
    );
}

/* Status Filter */

if(!empty($_GET['status']))
{
    $status =
    mysqli_real_escape_string(
    $conn,
    $_GET['status']
    );

    $statusWhere =
    " AND invoices.status='$status' ";
}

/* Date Filter */

if(
!empty($_GET['from_date'])
&&
!empty($_GET['to_date'])
)
{
    $from =
    mysqli_real_escape_string(
    $conn,
    $_GET['from_date']
    );

    $to =
    mysqli_real_escape_string(
    $conn,
    $_GET['to_date']
    );

    $dateWhere =
    " AND invoices.invoice_date BETWEEN '$from' AND '$to' ";

    $fileName .=
    "_".
    date(
    'm_Y',
    strtotime($from)
    );
}
else
{
    $fileName .=
    "_".
    date('m_Y');
}

$fileName =
preg_replace(
'/[^A-Za-z0-9_-]/',
'_',
$fileName
);

header(
"Content-Type: application/vnd.ms-excel"
);

header(
"Content-Disposition: attachment; filename=".$fileName.".xls"
);

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

WHERE 1=1

$searchWhere

$statusWhere

$dateWhere

GROUP BY invoices.id

ORDER BY invoices.id DESC"

);

if(!$q)
{
    die(mysqli_error($conn));
}

echo "<table border='1'>";

echo "

<tr>

<th>Invoice No</th>

<th>Customer Name</th>

<th>Invoice Date</th>

<th>Taxable Amount</th>

<th>GST Amount</th>

<th>Total Amount</th>

<th>Received Amount</th>

<th>Balance Amount</th>

<th>Payment Type</th>

<th>Status</th>

</tr>

";

while($row=mysqli_fetch_assoc($q))
{

$gstAmount =

(float)$row['cgst_amount']

+

(float)$row['sgst_amount']

+

(float)$row['igst_amount'];

$paidAmount =
(float)$row['received_amount'];

$balance =
(float)$row['grand_total']
-
$paidAmount;

if($paidAmount <= 0)
{
    $paymentType = "UNPAID";
}
elseif($balance > 0)
{
    $paymentType = "PARTIAL";
}
else
{
    $paymentType = "PAID";
}

echo "

<tr>

<td>".$row['invoice_no']."</td>

<td>".$row['customer_name']."</td>

<td>".date(
'd/m/Y',
strtotime($row['invoice_date'])
)."</td>

<td>".$row['taxable_value']."</td>

<td>".$gstAmount."</td>

<td>".$row['grand_total']."</td>

<td>".$paidAmount."</td>

<td>".$balance."</td>

<td>".$paymentType."</td>

<td>".$row['status']."</td>

</tr>

";

}

echo "</table>";

exit;