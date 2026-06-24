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

$invoice_id   = $_POST['invoice_id'];
$payment_date = $_POST['payment_date'];
$amount       = $_POST['amount'];
$payment_mode = $_POST['payment_mode'];
$reference_no = $_POST['reference_no'];
$remarks      = $_POST['remarks'];

/* Invoice Total */

$invoice = mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT grand_total
FROM invoices
WHERE id='$invoice_id'"

)

);

$grandTotal =
$invoice['grand_total'];

/* Previous Payments */

$paid = mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT
IFNULL(SUM(amount),0) total

FROM payments

WHERE invoice_id='$invoice_id'"

)

);

$totalPaidBefore =
$paid['total'];

/* New Balance */

$balanceAmount =
$grandTotal -
(
$totalPaidBefore +
$amount
);

/* Save Payment */

mysqli_query(

$conn,

"INSERT INTO payments(

invoice_id,
payment_date,
amount,
balance_amount,
payment_mode,
reference_no,
remarks

)

VALUES(

'$invoice_id',

'$payment_date',

'$amount',

'$balanceAmount',

'$payment_mode',

'$reference_no',

'$remarks'

)"

);

$invoiceId = $invoice_id;

$invoice =
mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT grand_total

FROM invoices

WHERE id='$invoiceId'"

)

);

$payment =
mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT

IFNULL(
SUM(amount),
0
) total

FROM payments

WHERE invoice_id='$invoiceId'"

)

);

if(
$payment['total']

> =
> $invoice['grand_total']
> )
> {

mysqli_query(

$conn,

"UPDATE invoices

SET status='Inactive'

WHERE id='$invoiceId'"

);

}
else
{

mysqli_query(

$conn,

"UPDATE invoices

SET status='Active'

WHERE id='$invoiceId'"

);

}


header("Location:list.php");
exit;