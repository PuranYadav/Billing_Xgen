<?php

include "../auth.php";
include "../db.php";

include "../common_functions.php";

$customers = mysqli_query(
$conn,
"SELECT * FROM customers ORDER BY customer_name ASC"
);

?>

<!DOCTYPE html>

<html>

<head>

<title>Create Invoice</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    border:none;
    border-radius:12px;
}

.card-header{
    padding:15px 20px;
}

.form-label{
    font-weight:600;
}

.form-control{
    height:42px;
}

.calculated{
    background:#f8f9fa;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container mt-4">

<a href="list.php" class="btn btn-secondary mb-3">
← Back
</a>

<form method="POST" action="save.php">

<div class="card shadow-lg">

<div class="card-header bg-primary text-white">
    <h4 class="mb-0">Create Invoice</h4>
</div>

<div class="card-body">

<div class="row g-3">

<div class="col-md-6">
<label class="form-label">Customer</label>

<select
name="customer_id"
class="form-control"
required>

<option value="">
Select Customer
</option>

<?php while($c=mysqli_fetch_assoc($customers)){ ?>

<option value="<?= $c['id']; ?>">
<?= $c['customer_name']; ?>
</option>

<?php } ?>

</select>
</div>

<div class="col-md-6">
<label class="form-label">Reverse Charge</label>

<select
name="reverse_charge"
class="form-control">

<option value="NO">NO</option>
<option value="YES">YES</option>

</select>
</div>

<div class="col-md-6">
<label class="form-label">Invoice Date</label>

<input
type="date"
name="invoice_date"
class="form-control"
required>

</div>

<div class="col-md-6">

<label>

Payment Due Date

</label>


<input
type="date"
name="payment_due_date"
class="form-control">

<small class="text-muted">

Leave blank to auto calculate +30 days

</small>

</div>


<div class="col-md-6">
<label class="form-label">Period From</label>

<input
type="date"
name="period_from"
class="form-control">

</div>

<div class="col-md-6">
<label class="form-label">Period To</label>

<input
type="date"
name="period_to"
class="form-control">

</div>


<hr class="mt-4">

<div class="col-md-4">
<label class="form-label">Amount</label>

<input
type="number"
step="0.01"
id="amount"
name="amount"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">FOV</label>

<input
type="number"
step="0.01"
id="fov"
name="fov_amount"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">Other Charges</label>

<input
type="number"
step="0.01"
id="other"
name="other_charge"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">Fuel %</label>

<input
type="number"
step="0.01"
id="fuel_percent"
name="fuel_percent"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">Fuel Charge</label>

<input
readonly
id="fuel_charge"
name="fuel_charge"
class="form-control calculated">

</div>

<div class="col-md-4">
<label class="form-label">Taxable Value</label>

<input
readonly
id="taxable_value"
name="taxable_value"
class="form-control calculated">

</div>

<div class="col-md-4">
<label class="form-label">CGST %</label>

<input
type="number"
value="9"
id="cgst_rate"
name="cgst_rate"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">SGST %</label>

<input
type="number"
value="9"
id="sgst_rate"
name="sgst_rate"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">IGST %</label>

<input
type="number"
step="0.01"
value="0"
id="igst_rate"
name="igst_rate"
class="form-control">

</div>

<div class="col-md-4">
<label class="form-label">Grand Total</label>

<input
readonly
id="grand_total"
name="grand_total"
class="form-control calculated">

</div>

<div class="col-md-8 d-flex justify-content-end align-items-end">

<input type="hidden" name="cgst_amount" id="cgst_amount">

<input type="hidden" name="sgst_amount" id="sgst_amount">

<input type="hidden" name="igst_amount" id="igst_amount">

<button
type="submit"
class="btn btn-success px 4"
style="height:42px;">

Save Invoice

</button>

</div>


</div>

</div>



<!-- <div class="card-footer text-end">

<input type="hidden" name="cgst_amount" id="cgst_amount">
<input type="hidden" name="sgst_amount" id="sgst_amount">
<input type="hidden" name="igst_amount" id="igst_amount">

<button
type="submit"
class="btn btn-success">

Save Invoice

</button>

</div> -->

</div>

</form>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
function calculateInvoice()
{


let amount =
Number($("#amount").val()) || 0;

let fov =
Number($("#fov").val()) || 0;

let other =
Number($("#other").val()) || 0;

let fuelPercent =
Number($("#fuel_percent").val()) || 0;

let cgstRate =
Number($("#cgst_rate").val()) || 0;

let sgstRate =
Number($("#sgst_rate").val()) || 0;

let igstRate =
Number($("#igst_rate").val()) || 0;


/* Fuel Charge */

let fuelCharge =
Number(((amount * fuelPercent) / 100).toFixed(2));


/* Taxable Value */

let taxable =
Number((
    amount +
    fov +
    other +
    fuelCharge
).toFixed(2));


/* GST */

let cgstAmount =
Number(((taxable * cgstRate) / 100).toFixed(2));

let sgstAmount =
Number(((taxable * sgstRate) / 100).toFixed(2));

let igstAmount =
Number(((taxable * igstRate) / 100).toFixed(2));


/* Grand Total */

let grandTotal =
Number((
    taxable +
    cgstAmount +
    sgstAmount +
    igstAmount
).toFixed(2));


$("#fuel_charge").val(
    fuelCharge.toFixed(2)
);

$("#taxable_value").val(
    taxable.toFixed(2)
);

$("#cgst_amount").val(
    cgstAmount.toFixed(2)
);

$("#sgst_amount").val(
    sgstAmount.toFixed(2)
);

$("#igst_amount").val(
    igstAmount.toFixed(2)
);

$("#grand_total").val(
    grandTotal.toFixed(2)
);


}


$("#amount,#fov,#other,#fuel_percent,#cgst_rate,#sgst_rate,#igst_rate")
.on(
"keyup change",
calculateInvoice
);

calculateInvoice();

$("#amount,#fov,#other").on("blur", function(){

    let val = Number($(this).val()) || 0;

    $(this).val(val.toFixed(2));

});
</script>

</body>
</html>
