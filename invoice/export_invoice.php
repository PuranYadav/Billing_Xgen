<?php

include "../auth.php";
include "../db.php";

$search = "";

$fileName = "Invoice_Report_".date('d-m-Y');

if(!empty($_GET['search']))
{
    $keyword =
    mysqli_real_escape_string(
    $conn,
    $_GET['search']
    );

    $search = "

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
    )."_Invoice_Report";
}

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

customers.customer_name

FROM invoices

LEFT JOIN customers
ON customers.id=invoices.customer_id

WHERE 1=1

$search

ORDER BY invoices.id DESC"

);

echo "<table border='1'>";

echo "

<tr>

<th>Invoice No</th>

<th>Customer Name</th>

<th>Invoice Date</th>

<th>Taxable Amount</th>

<th>GST Amount</th>

<th>Total Amount</th>

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

echo "

<tr>

<td>".$row['invoice_no']."</td>

<td>".$row['customer_name']."</td>

<td style='mso-number-format:\"dd/mm/yyyy\";'>

".date(
'd/m/Y',
strtotime($row['invoice_date'])
)."

</td>

<td>".$row['taxable_value']."</td>

<td>".$gstAmount."</td>

<td>".$row['grand_total']."</td>

</tr>

";

}

echo "</table>";

exit;