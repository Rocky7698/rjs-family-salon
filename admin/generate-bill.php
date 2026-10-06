<?php
session_start();
include '../config/db.php';

$aid = (int)$_GET['id'];

$app = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM appointments WHERE id=$aid")
);
if(!$app) die("Invalid appointment");

/* All services */
$services = [];
$res = mysqli_query($conn,"SELECT * FROM services");
while($r = mysqli_fetch_assoc($res)) {
    $services[] = $r;
}

/* Find booked service ID */
$bookedServiceId = 0;
foreach ($services as $s) {
    if (strtolower(trim($s['service_name'])) === strtolower(trim($app['service']))) {
        $bookedServiceId = $s['id'];
        break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Generate Bill</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container my-3">
<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-3">RJ'S FAMILY SALON</h4>

<p><b>Customer:</b> <?= htmlspecialchars($app['name']) ?></p>
<p><b>Mobile:</b> <?= htmlspecialchars($app['mobile']) ?></p>

<form method="POST" action="save-bill.php">

<input type="hidden" name="appointment_id" value="<?= $aid ?>">

<hr>

<div id="servicesArea"></div>

<button type="button"
        class="btn btn-secondary btn-sm mb-3"
        onclick="addService()">
+ Add Service
</button>

<hr>

<label class="fw-bold">Discount (₹)</label>
<input type="number"
       name="discount"
       value="0"
       min="0"
       class="form-control mb-3"
       oninput="calcTotal()">

<label class="fw-bold">Payment Mode</label>
<select name="payment_mode" class="form-control mb-3">
    <option>Credit</option>
    <option>Debit</option>
    <option>Cash</option>
    <option>UPI</option>
</select>

<p class="mb-1">Subtotal: ₹ <span id="subtotal">0</span></p>
<h5>Total: ₹ <span id="total">0</span></h5>

<button class="btn btn-dark w-100 mt-3">
Save & Generate Bill
</button>

</form>

</div>
</div>
</div>

<script>
const services = <?= json_encode($services) ?>;

/* ADD SERVICE ROW */
function addService(defaultId = '') {

    let row = document.createElement('div');
    row.className = 'row mb-2 service-row';

    let options = '<option value="">Select Service</option>';
    services.forEach(s => {
        let selected = (s.id == defaultId) ? 'selected' : '';
        options += `<option value="${s.id}" data-price="${s.price}" ${selected}>
                        ${s.service_name}
                    </option>`;
    });

    row.innerHTML = `
        <div class="col-7">
            <select name="service_id[]" class="form-control service-select">
                ${options}
            </select>
        </div>
        <div class="col-4">
            <input type="number" class="form-control price" value="0" readonly>
        </div>
        <div class="col-1 text-end">
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="this.closest('.service-row').remove(); calcTotal();">
                ×
            </button>
        </div>
    `;

    document.getElementById('servicesArea').appendChild(row);

    let select = row.querySelector('.service-select');
    let priceInput = row.querySelector('.price');

    select.onchange = function () {
        let price = this.options[this.selectedIndex].getAttribute('data-price');
        if (!price) price = 0;
        priceInput.value = parseFloat(price);
        calcTotal();
    };

    /* auto trigger for booked service */
    if (defaultId) {
        select.value = defaultId;
        select.dispatchEvent(new Event('change'));
    }
}

/* CALCULATE TOTAL */
function calcTotal() {

    let subtotal = 0;

    document.querySelectorAll('.price').forEach(input => {
        let val = parseFloat(input.value);
        if (!isNaN(val)) subtotal += val;
    });

    document.getElementById('subtotal').innerText = subtotal;

    let discountInput = document.querySelector('[name="discount"]');
    let discount = parseFloat(discountInput.value);
    if (isNaN(discount)) discount = 0;

    let total = subtotal - discount;
    if (total < 0) total = 0;

    document.getElementById('total').innerText = total;
}

/* AUTO ADD APPOINTMENT SERVICE */
addService(<?= (int)$bookedServiceId ?>);
calcTotal();
</script>

</body>
</html>
