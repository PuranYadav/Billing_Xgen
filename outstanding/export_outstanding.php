<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include "../auth.php";
include "../db.php";

$keyword = "";
$fileName = "Outstanding_Report";

/* Search Filter */

if(!empty($_GET['search']))
{
    $keyword =
    mysqli_real_escape_string(
    $conn,
    $_GET['search']
    );

    $fileName =
    str_replace(
    ' ',
    '_',
    trim($_GET['search'])
    );
}

/* Month in Filename */

if(!empty($_GET['month']))
{
    $fileName .=
    "_".
    sprintf(
    '%02d',
    (int)$_GET['month']
    );
}
else
{
    $fileName .= "_All_Months";
}

/* Year in Filename */

$fileName .=
"_".
(
!empty($_GET['year'])
?
$_GET['year']
:
date('Y')
);

/* Clean Filename */

$fileName =
preg_replace(
'/[^A-Za-z0-9_-]/',
'_',
$fileName
);

/* Excel Headers */

header(
"Content-Type: application/vnd.ms-excel"
);

header(
"Content-Disposition: attachment; filename=".$fileName.".xls"
);

/* Filters */

$where = "";
$search = "";

if(!empty($_GET['month']))
{
    $month = (int)$_GET['month'];

    $where .= "

    AND MONTH(i.invoice_date) = '$month'

    ";
}

if(!empty($_GET['year']))
{
    $year = (int)$_GET['year'];

    $where .= "

    AND YEAR(i.invoice_date) = '$year'

    ";
}

if(!empty($_GET['search']))
{
    $search .= "

    AND c.customer_name
    LIKE '%$keyword%'

    ";
}

/* Query */

$result = mysqli_query(

$conn,

"SELECT

c.customer_name,

i.invoice_no,

i.invoice_date,

i.payment_due_date,

i.grand_total,

IFNULL(
SUM(p.amount),
0
) AS received_amount,

(
i.grand_total -
IFNULL(
SUM(p.amount),
0
)
) AS balance_amount

FROM invoices i

LEFT JOIN customers c
ON c.id = i.customer_id

LEFT JOIN payments p
ON p.invoice_id = i.id

WHERE 1=1

$where

$search

GROUP BY i.id

HAVING balance_amount > 0

ORDER BY i.invoice_date DESC"

);

if(!$result)
{
    die(mysqli_error($conn));
}

/* Excel Table */

echo "<table border='1'>";

echo "

<tr>

<th>Customer Name</th>

<th>Invoice No</th>

<th>Invoice Date</th>

<th>Due Date</th>

<th>Total Amount</th>

<th>Received Amount</th>

<th>Balance Amount</th>

</tr>

";

$totalOutstanding = 0;

while($row = mysqli_fetch_assoc($result))
{

$totalOutstanding += $row['balance_amount'];

echo "

<tr>

<td>".$row['customer_name']."</td>

<td>".$row['invoice_no']."</td>

<td style='mso-number-format:\"dd\/mm\/yyyy\";'>

".date(
'd/m/Y',
strtotime($row['invoice_date'])
)."

</td>

<td style='mso-number-format:\"dd\/mm\/yyyy\";'>

".(

!empty($row['payment_due_date'])
&&
$row['payment_due_date']!='0000-00-00'

?

date(
'd/m/Y',
strtotime($row['payment_due_date'])
)

:

''

)."

</td>

<td>".number_format(
$row['grand_total'],
2
)."</td>

<td>".number_format(
$row['received_amount'],
2
)."</td>

<td>".number_format(
$row['balance_amount'],
2
)."</td>

</tr>

";

}

echo "

<tr>

<td colspan='6'
align='right'>

<b>Total Outstanding</b>

</td>

<td>

<b>".number_format(
$totalOutstanding,
2
)."</b>

</td>

</tr>

";

echo "</table>";

exit;

?>