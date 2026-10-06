<?php
include '../config/db.php';

$id = (int)$_GET['id'];

$bill = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM bills WHERE id=$id")
);

$items = mysqli_query(
    $conn,
    "SELECT * FROM bill_items WHERE bill_id=$id"
);

$msg = "RJ'S FAMILY SALON\n";
$msg .= "Customer: {$bill['customer_name']}\n";

while ($i = mysqli_fetch_assoc($items)) {
    $msg .= "{$i['service_name']} - ₹{$i['price']}\n";
}

$msg .= "Total: ₹{$bill['total']}\n";
$msg .= "Payment: {$bill['payment_mode']}\nThank you!";

function site_url($path = '')
{
    return "http://localhost/rjs-salon/" . ltrim($path, '/');
}

$pdfRelativePath = "admin/bills/bills/bill_$id.pdf";

$pdfUrl = site_url($pdfRelativePath);

$pdfDiskPath = $_SERVER['DOCUMENT_ROOT'] . "/rjs-salon/" . $pdfRelativePath;

$wa = "https://wa.me/91{$bill['customer_mobile']}?text=" .
    urlencode(
        "RJ'S FAMILY SALON\n\nYour Bill:\n" . $pdfUrl
    );
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-3">
        <div class="card shadow text-center">
            <div class="card-body">

                <h5>RJ'S FAMILY SALON</h5>
                <p><?= $bill['customer_name'] ?></p>

                <hr>

                <?php
                $r = mysqli_query($conn, "SELECT * FROM bill_items WHERE bill_id=$id");
                while ($x = mysqli_fetch_assoc($r)) {
                    echo "{$x['service_name']} - ₹{$x['price']}<br>";
                }
                ?>

                <hr>
                <b>Total: ₹<?= $bill['total'] ?></b><br>
                <?= $bill['payment_mode'] ?>

                <a href="<?= $wa ?>" target="_blank" class="btn btn-success w-100 mt-2">
                    Send Bill on WhatsApp
                </a>

                <?php if (file_exists($pdfDiskPath)) { ?>
                    <!-- ✅ FIXED DOWNLOAD BUTTON -->
                    <a href="<?= $pdfUrl ?>"
                        class="btn btn-dark w-100 mb-2 mt-2"
                        download="RJ_Salon_Bill_<?= $id ?>.pdf">
                        ⬇️ Download PDF Bill
                    </a>
                <?php } else { ?>
                    <div class="alert alert-danger mt-3 mb-2">
                        PDF file nahi mili (bill_<?= $id ?>.pdf). <br>
                        Pehle PDF generate hona chahiye.
                    </div>

                    <div class="small text-muted">
                        Expected PDF URL: <?= $pdfUrl ?>
                    </div>
                <?php } ?>

                <p class="text-muted text-center small mt-2">
                    Download karke manually WhatsApp par send karein
                </p>
                <form method="post" action="dashboard.php">
                    <button class="btn btn-success">✔ Bill Sent</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>