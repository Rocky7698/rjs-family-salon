<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, monospace;
            font-size: 10px;
            margin: 0;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .total {
            font-size: 12px;
            font-weight: bold;
        }

        .small {
            font-size: 9px;
        }
    </style>
</head>

<body>

    <div class="center bold">
        RJ'S FAMILY SALON
    </div>

    <div class="center small">
        Navsari<br>
        <?= date('d-m-Y h:i A', strtotime($bill['created_at'])) ?>
    </div>
    <hr>

    Invoice No: <?= $bill['id'] ?> <br>

    <hr>

    <hr>

    Customer: <?= htmlspecialchars($app['name']) ?><br>
    Mobile: <?= htmlspecialchars($app['mobile']) ?>

    <hr>

    <table>
        <?php foreach ($bill_items as $it): ?>
            <tr>
                <td><?= htmlspecialchars($it['service_name']) ?></td>
                <td class="right">₹<?= number_format($it['price'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="right">₹<?= number_format($subtotal, 2) ?></td>
        </tr>
        <tr>
            <td>Discount</td>
            <td class="right">₹<?= number_format($discount, 2) ?></td>
        </tr>
        <tr class="total">
            <td>TOTAL</td>
            <td class="right">₹<?= number_format($total, 2) ?></td>
        </tr>
    </table>

    <hr>

    Payment: <?= htmlspecialchars($payment) ?>

    <hr>

    <div class="center bold">
        THANK YOU 🙏<br>
        Visit Again
    </div>

    <div class="center small">
        * Computer generated receipt *
    </div>

</body>

</html>