<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";


$id = $_POST['id'];

mysqli_query(
$conn,

"UPDATE invoices SET

customer_id='".$_POST['customer_id']."',
reverse_charge='".$_POST['reverse_charge']."',

invoice_date='".$_POST['invoice_date']."',

payment_due_date='".$_POST['payment_due_date']."',

period_from='".$_POST['period_from']."',
period_to='".$_POST['period_to']."',

amount='".$_POST['amount']."',
fov_amount='".$_POST['fov_amount']."',
other_charge='".$_POST['other_charge']."',

fuel_percent='".$_POST['fuel_percent']."',
fuel_charge='".$_POST['fuel_charge']."',

taxable_value='".$_POST['taxable_value']."',

cgst_rate='".$_POST['cgst_rate']."',
cgst_amount='".$_POST['cgst_amount']."',

sgst_rate='".$_POST['sgst_rate']."',
sgst_amount='".$_POST['sgst_amount']."',

igst_rate='".$_POST['igst_rate']."',
igst_amount='".$_POST['igst_amount']."',

grand_total='".$_POST['grand_total']."'

WHERE id='$id'"

);

header("Location:list.php");
exit;