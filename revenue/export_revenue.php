<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";


$fileName = "Revenue_Report";

if(!empty($_GET['search']))
{
    $fileName =
    str_replace(
    ' ',
    '_',
    trim($_GET['search'])
    );
}

if(
!empty($_GET['from_date'])
&&
!empty($_GET['to_date'])
)
{

    $fileName .= "_".

    date(
    'M_Y',
    strtotime($_GET['from_date'])
    )

    ."_to_".

    date(
    'M_Y',
    strtotime($_GET['to_date'])
    );

}
else
{

    $fileName .= "_All_Date";

}

$fileName .= "_Revenue_Report";

// moth wise 
// $fileName = "Revenue_Report";

// if(!empty($_GET['search']))
// {
//     $fileName =
//     str_replace(
//     ' ',
//     '_',
//     $_GET['search']
//     );
// }

// if(!empty($_GET['month']))
// {
//     $fileName .= "_".
//     date(
//     'F',
//     mktime(
//     0,
//     0,
//     0,
//     $_GET['month'],
//     1
//     )
//     );
// }
// else
// {
//     $fileName .= "_All_Months";
// }

// $fileName .= "_".$_GET['year'];

// $fileName .= "_Revenue_Report";

header(
"Content-Type: application/vnd.ms-excel"
);

header(
"Content-Disposition: attachment; filename=".$fileName.".xls"
);

$where = "";
$search = "";

if(
!empty($_GET['from_date'])
&&
!empty($_GET['to_date'])
)
{
    $where .= "

    AND i.invoice_date
    BETWEEN '".$_GET['from_date']."'
    AND '".$_GET['to_date']."'

    ";
}

if(!empty($_GET['search']))
{
    $keyword =
    mysqli_real_escape_string(
    $conn,
    $_GET['search']
    );

    $search .= "

    AND c.customer_name
    LIKE '%$keyword%'

    ";
}

// month wise 
// $where = "";
// $search = "";

// if(!empty($_GET['month']))
// {
//     $month = (int)$_GET['month'];

//     $where .= "

//     AND MONTH(i.invoice_date)='$month'

//     ";
// }

// if(!empty($_GET['year']))
// {
//     $year = (int)$_GET['year'];

//     $where .= "

//     AND YEAR(i.invoice_date)='$year'

//     ";
// }

// if(!empty($_GET['search']))
// {
//     $keyword =
//     mysqli_real_escape_string(
//     $conn,
//     $_GET['search']
//     );

//     $search .= "

//     AND c.customer_name
//     LIKE '%$keyword%'

//     ";
// }

$result = mysqli_query(

$conn,

"SELECT

c.customer_name,

i.invoice_no,

i.invoice_date,

i.grand_total,

IFNULL(
SUM(p.amount),
0
) received_amount,

(
i.grand_total -
IFNULL(
SUM(p.amount),
0
)
) balance_amount

FROM invoices i

LEFT JOIN customers c
ON c.id=i.customer_id

LEFT JOIN payments p
ON p.invoice_id=i.id

WHERE 1=1

$where

$search

GROUP BY i.id

ORDER BY i.invoice_date DESC"

);
echo "<table border='1'>";

echo "

<tr>

<th>Customer</th>

<th>Invoice No</th>

<th>Invoice Date</th>

<th>Total Revenue</th>

<th>Received</th>

<th>Balance</th>

</tr>

";

$totalRevenue = 0;
$totalReceived = 0;
$totalBalance = 0;

while($row=mysqli_fetch_assoc($result))
{

$totalRevenue += $row['grand_total'];
$totalReceived += $row['received_amount'];
$totalBalance += $row['balance_amount'];

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

<td colspan='3' align='right'>

<b>Grand Total</b>

</td>

<td>

<b>".number_format(
$totalRevenue,
2
)."</b>

</td>

<td>

<b>".number_format(
$totalReceived,
2
)."</b>

</td>

<td>

<b>".number_format(
$totalBalance,
2
)."</b>

</td>

</tr>

";

echo "</table>";

exit;