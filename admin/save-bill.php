<?php
include '../config/db.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

$aid = (int)$_POST['appointment_id'];
$discount = (float)$_POST['discount'];
$payment = $_POST['payment_mode'] ?? 'Cash';

$app = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM appointments WHERE id=$aid")
);
if (!$app) die("Invalid appointment");

/* Create bill */
mysqli_query($conn, "
INSERT INTO bills
(appointment_id, customer_name, customer_mobile, discount, payment_mode, total)
VALUES
($aid,'{$app['name']}','{$app['mobile']}', $discount, '$payment', 0)
");

$bill_id = mysqli_insert_id($conn);
$subtotal = 0;

/* Insert bill items */
foreach ($_POST['service_id'] as $sid) {
    $sid = (int)$sid;
    if (!$sid) continue;

    $s = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT service_name,price FROM services WHERE id=$sid")
    );

    $subtotal += (float)$s['price'];

    mysqli_query($conn, "
    INSERT INTO bill_items (bill_id, service_name, price)
    VALUES ($bill_id,'{$s['service_name']}',{$s['price']})
    ");
}

$total = max(0, $subtotal - $discount);

mysqli_query($conn, "UPDATE bills SET total=$total WHERE id=$bill_id");
mysqli_query($conn, "UPDATE appointments SET status='Completed' WHERE id=$aid");

/* ✅ PDF GENERATE + SAVE */
/* ================= TEMPLATE LOAD ================= */
ob_start();
include __DIR__ . '/bill-pdf-template.php';
$html = ob_get_clean();

/* 58mm thermal size */
/* ================= PDF ================= */
$dompdf = new Dompdf([
    'defaultFont' => 'DejaVu Sans'
]);
$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 226.77, 700], 'portrait');
$dompdf->render();

$pdfContent = $dompdf->output();

/* ✅ SAVE LOCATION (same as your view-bill.php expects) */
$pdfDir = __DIR__ . "/bills/bills";
if (!is_dir($pdfDir)) {
    mkdir($pdfDir, 0777, true);
}

$pdfPath = $pdfDir . "/bill_" . $bill_id . ".pdf";
file_put_contents($pdfPath, $pdfContent);

/* Redirect */
header("Location: view-bill.php?id=$bill_id");
exit;
