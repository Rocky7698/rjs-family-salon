<?php
include '../config/db.php';

$bill_id = (int)($_POST['bill_id'] ?? 0);
if ($bill_id > 0) {
    mysqli_query($conn,
        "UPDATE bills SET sent_on=NOW() WHERE id=$bill_id"
    );
}

header("Location: dashboard.php");
exit;
