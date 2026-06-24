<?php

include "../auth.php";
include "../db.php";
include "../common_functions.php";

/* Generate Invoice Number */

$nextId = 1;

$q = mysqli_query(
$conn,
"SELECT id
FROM invoices
ORDER BY id DESC
LIMIT 1"
);

if(mysqli_num_rows($q) > 0)
{
    $r = mysqli_fetch_assoc($q);
    $nextId = $r['id'] + 1;
}

$invoiceNo =
"Sales/".
date("y").
"-27/".
str_pad(
$nextId,
4,
"0",
STR_PAD_LEFT
);

/* Invoice Date */

$invoice_date =
$_POST['invoice_date'];

/* Due Date Logic */

if(!empty($_POST['payment_due_date']))
{
    $payment_due_date =
    $_POST['payment_due_date'];
}
else
{
    $credit_days =
    !empty($_POST['credit_days'])
    ?
    (int)$_POST['credit_days']
    :
    30;

    $payment_due_date =
    date(
    'Y-m-d',
    strtotime(
    $invoice_date." +".$credit_days." days"
    )
    );
}

/* Insert Invoice */

mysqli_query(

$conn,

"INSERT INTO invoices(

invoice_no,
customer_id,
invoice_date,
payment_due_date,
period_from,
period_to,
amount,
fov_amount,
other_charge,
fuel_percent,
fuel_charge,
taxable_value,
cgst_rate,
cgst_amount,
sgst_rate,
sgst_amount,
grand_total,
reverse_charge,
igst_rate,
igst_amount

)

VALUES(

'$invoiceNo',

'".$_POST['customer_id']."',

'$invoice_date',

'$payment_due_date',

'".$_POST['period_from']."',

'".$_POST['period_to']."',

'".$_POST['amount']."',

'".$_POST['fov_amount']."',

'".$_POST['other_charge']."',

'".$_POST['fuel_percent']."',

'".$_POST['fuel_charge']."',

'".$_POST['taxable_value']."',

'".$_POST['cgst_rate']."',

'".$_POST['cgst_amount']."',

'".$_POST['sgst_rate']."',

'".$_POST['sgst_amount']."',

'".$_POST['grand_total']."',

'".$_POST['reverse_charge']."',

'".$_POST['igst_rate']."',

'".$_POST['igst_amount']."'

)"

);

header("Location:list.php");
exit;