<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

$id = intval($_GET['id']);

$sql = "
SELECT
i.*,
c.customer_name,
c.billing_address,
c.pincode,
c.gst_number,
c.state_name,
c.state_code,
c.po_number
FROM invoices i
LEFT JOIN customers c
ON c.id=i.customer_id
WHERE i.id='$id'
";

$result = mysqli_query($conn,$sql);

$data = mysqli_fetch_assoc($result);

if(!$data){
die("Invoice Not Found");
}
function amountToWords($number)
{
    $ones = array(
        "",
        "One",
        "Two",
        "Three",
        "Four",
        "Five",
        "Six",
        "Seven",
        "Eight",
        "Nine",
        "Ten",
        "Eleven",
        "Twelve",
        "Thirteen",
        "Fourteen",
        "Fifteen",
        "Sixteen",
        "Seventeen",
        "Eighteen",
        "Nineteen"
    );

    $tens = array(
        "",
        "",
        "Twenty",
        "Thirty",
        "Forty",
        "Fifty",
        "Sixty",
        "Seventy",
        "Eighty",
        "Ninety"
    );

    if($number < 20)
    {
        return $ones[$number];
    }

    if($number < 100)
    {
        return $tens[floor($number/10)]
        ." ".
        $ones[$number%10];
    }

    if($number < 1000)
    {
        return $ones[floor($number/100)]
        ." Hundred ".
        amountToWords($number%100);
    }

    if($number < 100000)
    {
        return amountToWords(floor($number/1000))
        ." Thousand ".
        amountToWords($number%1000);
    }

    if($number < 10000000)
    {
        return amountToWords(floor($number/100000))
        ." Lakh ".
        amountToWords($number%100000);
    }

    return amountToWords(floor($number/10000000))
    ." Crore ".
    amountToWords($number%10000000);
}

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>
Invoice <?= $data['invoice_no']; ?>
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{
    margin:0;
    padding:0;
    font-family:Arial, Helvetica, sans-serif;
    font-size:9px;
    color:#000;
    background:#fff;
}

.invoice{
    width:200mm;
    min-height:287mm;
    margin:auto;
    background:#fff;
}

/* TABLES */

table{
    width:100%;
    border-collapse:collapse;
}

td,
th{
    border:1px solid #000;
    padding:2px 4px;
    vertical-align:top;
    font-size:9px;
    line-height:13px;
}

th{
    font-weight:bold;
}

/* COMMON */

.right{
    text-align:right;
}

.center{
    text-align:center;
}

.bold{
    font-weight:bold;
}

.upper{
    text-transform:uppercase;
}

/* HEADER */

.header-table,
.header-table td{
    border:none !important;
    padding:0 !important;
}

.header-table h2{
    font-size:20px;
    font-weight:700;
    margin:0;
}

.header-table h6{
    font-size:14px;
    line-height:14px;
    font-weight:bold;
    margin:0;
}

.header-table img{
    max-width:140px;
    height:auto;
}

/* TAX INVOICE TITLE */

h3{
    font-size:18px !important;
    font-weight:500 !important;
    text-align:center;
    margin:8px 0 12px 0 !important;
}

/* CUSTOMER BLOCK */

.customer-header{
    font-size:9px;
    font-weight:bold;
}

.customer-name{
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    margin-top:6px;
    margin-bottom:20px;
    line-height:15px;
}

.customer-address{
    font-size:9px;
    line-height:14px;
}

/* INVOICE DETAILS */

.invoice-label{
    width:42%;
}

.invoice-value{
    width:58%;
    text-align:right;
}

/* BANK SECTION */

.bank-row{
    font-size:9px;
    line-height:16px;
}

.bank-label{
    display:inline-block;
    width:95px;
    font-weight:bold;
}

/* PAYMENT ADVICE */

.payment-title{
    font-size:10px;
    font-weight:bold;
    text-align:center;
}

.payment-table td{
    font-size:9px;
    padding:2px 4px;
    height:22px;
}

/* SIGNATURE */

.sign-area{
    height:90px;
}

.authorized-sign{
    text-align:right;
    padding-right:20px;
    padding-top:25px;
    font-size:9px;
}

/* BILL ACKNOWLEDGEMENT */

.bill-title{
    text-align:center;
    font-size:10px;
    font-weight:bold;
    line-height:14px;
}

.receiver-box{
    height:70px;
    vertical-align:top;
    padding:4px;
}

.seal-box{
    height:70px;
    vertical-align:bottom;
    text-align:center;
    padding-bottom:8px;
    font-weight:bold;
}

/* PRINT */

@page{
    size:A4 portrait;
    margin:5mm;
}

@media print{

.print-btn{
    display:none;
}

body{
    margin:0;
    padding:0;
}

.invoice{
    width:200mm;
    min-height:287mm;
    margin:auto;
}

}


</style>

</head>

<body>

<div class="print-btn">

<a href="list.php" class="btn btn-secondary">
Back
</a>

<button
onclick="window.print();"
class="btn btn-primary">

Print Invoice

</button>
<!-- <a
href="save_pdf.php?id=<?= $data['id']; ?>"
class="btn btn-success"
onclick="return confirm('Generate and Save PDF?');">

Save PDF

</a> -->
<?php if(!empty($data['pdf_file'])){ ?>
<!-- 
<a
href="../invoices/<?= $data['pdf_file']; ?>"
target="_blank"
class="btn btn-success">

Open Saved PDF

</a> -->

<?php } ?>
</div>
<div class="container-fluid">

<div class="row justify-content-center">

<div class="col-lg-9">
</div>
</div>
</div>
<div class="invoice">

<!-- HEADER -->

<table class="header-table" width="100%">

<tr>

<td class="no-border" width="70%">

<h2 style="margin:0;">
 <span style="color: red;">X</span><span style="color:blue;">GEN </span> <span style="color: red;">L</span><span style="color:blue;">OGISTICS </span> <span style="color: red;">P</span><span style="color:blue;">VT.<span>  <span style="color: red;">L</span><span style="color:blue;">TD.</span>
</h2>
<br>
<div>
<h6>
2nd Floor, Khasra No. 1395/684/2,<br>
Khandsa Road, Gurugram, Haryana
</h6>
</div>

<br>
<h6>
Email :
 <a href="mailto:info@xgenlogistic.com">
info@xgenlogistic.com
</a>
</h6>

</td>



<td width="30%"
align="right"
style="border:none;vertical-align:top;">

<img
src="../assets/images/final logo.png"
style="
width:180px;
height:auto;
">

</td>

</tr>

</table>

<h3 class="center">
TAX INVOICE
</h3>

<!-- CUSTOMER + INVOICE --><!-- CUSTOMER + INVOICE -->

<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse;font-size:11px;">

<tr>

<td width="50%" rowspan="4" valign="top" style="padding:0;">

<div style="
padding:9px 9px;
font-size:16px;
font-weight:bold;
border-bottom:1px solid #000;
">

Customer Name | Billed To

</div>

<div style="
padding:10px;
height:100px;
">

<div style="
font-size:16px;
font-weight:600;
text-transform:uppercase;
margin-bottom:30px;
">

<?= strtoupper($data['customer_name']); ?>

</div>

<div style="
font-size:10px;
line-height:12px;
">

<?= nl2br($data['billing_address']); ?>
<br>
<?php if(!empty($data['state_name'])){ ?>
<br>
<?= $data['state_name']; ?>
<?php } ?>

<?php if(!empty($data['pincode'])){ ?>
<?= $data['pincode']; ?>
<?php } ?>

</div>

</div>

</td>

<td width="28%">
GST Number:
</td>

<td width="30%">
<!-- 
<?= $data['gst_number']; ?> -->

</td>

</tr>

<tr>

<td>
Invoice Period:
</td>

<td>

<?= date('d-M-Y',strtotime($data['period_from'])); ?>

&nbsp; To &nbsp;

<?= date('d-M-Y',strtotime($data['period_to'])); ?>

</td>

</tr>

<tr>

<td>
Invoice No:
</td>

<td>

<?= $data['invoice_no']; ?>

</td>

</tr>

<tr>

<td>
Invoice Date:
</td>

<td>

<?= date('d-M-Y',strtotime($data['invoice_date'])); ?>

</td>

</tr>

<!-- PO BLOCK -->

<tr>

<td valign="top">

<b>PO Number :</b>

<?= $data['po_number']; ?>

</td>

<td>
Amount :
</td>

<td align="right">

<?= indian_currency($data['amount']); ?>

</td>

</tr>

<!-- GST BLOCK -->

<tr>

<td rowspan="4" valign="top">

<b>GST Number :</b>

<?= $data['gst_number']; ?>

<br><br>

<b>STATE :</b>

<?= $data['state_name']; ?>

<br><br>

<b>Place of supply (state name & code)</b>

<br>
<!-- &nbsp;&nbsp;&nbsp;
<?= $data['state_name']; ?>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
<?= $data['state_code']; ?> -->

</td>

<td>

FOV / Insurance

</td>

<td align="right">

<?= indian_currency($data['fov_amount']); ?>

</td>

</tr>

<tr>

<td>
Other Charge
</td>

<td align="right">

<?= indian_currency($data['other_charge']); ?>

</td>

</tr>

<tr>

<td>

Fuel Charge

<?php if(!empty($data['fuel_percent'])){ ?>

&nbsp; @ <?= $data['fuel_percent']; ?>%

<?php } ?>

</td>

<td align="right">

<?= indian_currency($data['fuel_charge']); ?>

</td>

</tr>

<tr>

<td>

Taxable Value

</td>

<td align="right">

<?= indian_currency($data['taxable_value']); ?>

</td>

</tr>

<!-- REVERSE CHARGE BLOCK -->


<td rowspan="4" valign="top" style="padding:0;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<div style="padding:14px;">

<div style="font-weight:bold;">

Tax subject to reverse charge

</div>

<br>

<div style="display:flex;">

<div style="width:80px;text-align:center;font-weight:bold;">

<?= ($data['reverse_charge']=="YES") ? "✔ YES" : "YES"; ?>

</div>

<div style="width:80px;text-align:center;font-weight:bold;">

<?= ($data['reverse_charge']=="NO") ? "✔ NO" : "NO"; ?>

</div>

</div>

</div>
</td>

</table>

</td>

<td>
    CGST @ <?= $data['cgst_rate']; ?>%
</td>

<td align="right">

<?= indian_currency($data['cgst_amount']); ?>

</td>

</tr>

<tr>

<td>
    SGST @ <?= $data['sgst_rate']; ?>%
</td>

<td align="right">

<?= indian_currency($data['sgst_amount']); ?>

</td>

</tr>

<tr>

<td>

IGST @ <?= $data['igst_rate']; ?> %

</td>

<td align="right">

<?= indian_currency($data['igst_amount']); ?>

</td>

</tr>

<tr>

<td>

<b>Grand Total</b>

</td>

<td align="right">

<b>

<?= indian_currency($data['grand_total']); ?>

</b>

</td>

</tr>

</table>

<!-- SERVICE + BANK -->
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">

<tr>

<td style="padding:8px;">

<div style="display:flex;justify-content:space-between;font-weight:bold;">

<span>Description of service: Courier Service</span>

<span>SAC: 996812</span>

<span>STATE: Haryana</span>

<span>Code 06</span>

</div>

<hr style="margin:8px 0;">
<div style="line-height:22px;">

<div class="bank-row">
<span class="bank-label">
Bank Name:
</span>
KOTAK MAHINDRA BANK
</div>

<div class="bank-row">
<span class="bank-label">
Account Number:
</span>
5047916888
</div>

<div class="bank-row">
<span class="bank-label">
IFSC Code:
</span>
KKBK0000299
</div>

<div class="bank-row">
<span class="bank-label">
PAN No:
</span>
AAACX4436E
</div>

<div class="bank-row">
<span class="bank-label">
UAM No:
</span>

</div>

<div class="bank-row">
<span class="bank-label">
Payment Due Date:
</span>
<b>
<?php

if(
!empty($data['payment_due_date'])
&&
$data['payment_due_date'] != '0000-00-00'
)
{
    echo date(
    'd-m-Y',
    strtotime($data['payment_due_date'])
    );
}
else
{
    echo date(
    'd-m-Y',
    strtotime(
    $data['invoice_date'].' +30 days'
    )
    );
}

?>

</b>

</div>

</div>

<b>
Please pay cheque/Draft favouring <b>M/S XGEN LOGISTICS PVT.LTD</b>
</b>
<br>
<b>
Late Payments are subjects to an interest charged @ 5% p.m.
</b>

<br>
<?php

if(!empty($data['payment_due_date']))
{
    $days =
    floor(
    (
    strtotime($data['payment_due_date'])
    -
    strtotime($data['invoice_date'])
    )
    /
    86400
    );
}
else
{
    $days = 30;
}

?>
<b>
Payment should be made within <?= $days; ?>  days from the date of bill.
</b>

<br>

<b>
Any mistake / correction found in the Invoice has to be reported
in writing 7-working days from the receipt of the invoice.
</b>

<br>

<b>
THIS IS A COMPUTER GENERATED INVOICE & DOES NOT REQUIRE ANY SIGNATURES.
</b>
<br>
</div>

</td>

</tr>

</table>

<!-- PAYMENT ADVICE -->
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:10px;margin-top:0px;">

<tr>
    <td colspan="6" class="payment-title">
        Payment Advice (Please detach and return with your payment)
    </td>
</tr>

<tr>

<!-- LEFT SIDE -->
<td colspan="3" style="padding:4px 6px;">

    <b>Invoice No:</b>
    <?= $data['invoice_no']; ?>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    <b>Invoice Date:</b>
    <?= date('d-M-Y',strtotime($data['invoice_date'])); ?>

</td>

<!-- RIGHT SIDE -->
<td colspan="3" style="padding:4px 6px;">

    <b>Client Name:</b>
    <?= strtoupper($data['customer_name']); ?>

</td>

</tr>

<tr>
    <td style="padding:4px;"><b>Name of Bank</b></td>
    <td style="padding:4px;"><b>Cheq/DD Number</b></td>
    <td style="padding:4px;"><b>Cheq/DD Date</b></td>
    <td style="padding:4px;"><b>Invoice Amount (Rs.)</b></td>
    <td style="padding:4px;"><b>TDS (Rs.)</b></td>
    <td style="padding:4px;"><b>Net Amt (Rs.)</b></td>
</tr>

<tr style="height:22px;">
    <td>&nbsp;</td>
    <td></td>
    <td></td>

    <td align="right" style="padding-right:5px;">
        <?= indian_currency($data['grand_total']); ?>
    </td>

    <td></td>

    <td></td>
</tr>

<tr style="height:22px;">

    <td colspan="3" style="padding-left:8px;">
        <b>Remark:</b>
    </td>

    <td colspan="3" style="padding-left:15px;">

        <b>Total:</b>

        <?= amountToWords($data['grand_total']); ?>   Rupees only.

    </td>

</tr>

</table>

<!-- SIGNATURE AREA -->

<table width="100%" border="0" cellspacing="0" cellpadding="0" >
    
<!-- <tr>

<td colspan="4"
align="right"
height="50"
style="
vertical-align:bottom;
padding-right:20px;
border-bottom:0px solid transparent !important;
">

For Xgen Logistics Private Limited

<br><br>

Authorised Signatory

</td> -->

</tr>

<tr>

<td colspan="6"
align="center"
style="
font-weight:bold;
border-top:0px solid transparent !important;
">

<b>XGEN LOGISTICS PVT. LTD</b>

<br>

Bill Acknowledgment

</td>

</tr>
<tr>

<td colspan="2" style="padding:6px 5px;">

    <b>Client Name:</b>

    <?= strtoupper($data['customer_name']); ?>

    <span style="margin-left:100px;">
        <b>Invoice Date:</b>

        <?= date('d-M-Y',strtotime($data['invoice_date'])); ?>
    </span>

    <span style="margin-left:150px;">
        <b>Net Amount: </b>

        <?= indian_currency($data['grand_total']); ?>
    </span>
   
</td>


</tr>

<tr style="height:55px;">

<td width="55%" valign="top" style="padding:4px;">

    <b>Name Of Receiver:</b>

    <br><br><br>

    <b>Received Date:</b>

</td>

<td class="seal-box">

    <b>Seal & Sign</b>

</td>

</tr>


</table>
</div>
<?php if(!empty($data['pdf_file'])){ ?>

<hr>

<div class="card mt-4">

<div class="card-header">

<h4>Saved PDF Preview</h4>

</div>

<div class="card-body">

<iframe
src="../invoices/<?= $data['pdf_file']; ?>"
width="100%"
height="800"
style="border:1px solid #ccc;">
</iframe>



</div>

<?php } ?>

</body>
</html>




