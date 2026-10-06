<?php
require_once __DIR__ . '/../vendor/autoload.php';
include '../config/db.php';

use Dompdf\Dompdf;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) die("Invalid ID");

/* Bill */
$bill = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM bills WHERE id=$id")
);
if (!$bill) die("Bill not found");

/* Items */
$items = mysqli_query($conn, "SELECT * FROM bill_items WHERE bill_id=$bill_id");

/* Bills folder */
$dir = __DIR__ . "/bills";
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

/* 🔥 IMPORTANT: template se HTML lo */
ob_start();
include __DIR__ . '/bill-pdf-template.php';   // ✅ YAHI FIX HAI
$html = ob_get_clean();

/* PDF */
$dompdf = new Dompdf([
    'defaultFont' => 'Courier'
]);

$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 164, 600]); // 58mm thermal
$dompdf->render();

$file = $dir . "/bill_$id.pdf";
file_put_contents($file, $dompdf->output());

/* Download */
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=bill_$id.pdf");
readfile($file);
exit;
