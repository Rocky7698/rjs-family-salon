<?php
session_start(); // MUST be first
include '../config/db.php';

$error = '';

/* 🔐 SESSION SAFETY CHECK */
if (!isset($_SESSION['otp_user_id'])) {
    die("Session expired. Please go back and request OTP again.");
}

$user_id = $_SESSION['otp_user_id'];

/* 🔐 VERIFY OTP */
if (isset($_POST['verify'])) {

    // Sanitize OTP
    $otp = trim($_POST['otp']);
    $otp = preg_replace('/\D/', '', $otp); // only digits

    $res = mysqli_query(
        $conn,
        "SELECT id FROM admin 
         WHERE id='$user_id'
         AND otp='$otp'
         AND otp_expires > NOW()
         LIMIT 1"
    );

    if (mysqli_num_rows($res) === 1) {
        header("Location: reset-password-otp.php");
        exit;
    } else {
        $error = "Invalid or expired OTP";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow">
                    <div class="card-body">

                        <h4 class="text-center mb-3">Verify OTP</h4>

                        <!-- 🔧 DEBUG OTP (REMOVE IN PRODUCTION) -->
                        <!-- <?php if (isset($_SESSION['debug_otp'])): ?>
                            <div class="alert alert-warning text-center">
                                <strong>TEST OTP:</strong>
                                <?= $_SESSION['debug_otp'] ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?> -->

                        <form method="POST" autocomplete="off">
                            <input type="text"
                                name="otp"
                                class="form-control mb-3"
                                placeholder="Enter OTP"
                                inputmode="numeric"
                                required>

                            <button name="verify"
                                class="btn btn-dark w-100">
                                Verify OTP
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>