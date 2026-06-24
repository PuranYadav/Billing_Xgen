<?php


include "../db.php";

$id = intval($_GET['id']);

$query = mysqli_query(
$conn,

"SELECT
i.*,
c.customer_name,
c.billing_address,
c.gst_number

FROM invoices i

LEFT JOIN customers c
ON c.id=i.customer_id

WHERE i.id='$id'"
);

$data = mysqli_fetch_assoc($query);

if(!$data)
{
    die("Invoice Not Found");
}

$html='

<h2>TAX INVOICE</h2>

<b>Customer :</b>
'.$data['customer_name'].'

<br>

<b>Invoice No :</b>
'.$data['invoice_no'].'

<br>

<b>Total :</b>
'.$data['grand_total'].'

';



