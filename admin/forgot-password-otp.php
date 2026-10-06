<?php
session_start(); // 🔴 MUST
include '../config/db.php';

$msg = '';
$error = '';

if (isset($_POST['send_otp'])) {

    // ✅ Sanitize mobile (important)
    $mobile = trim($_POST['mobile']);
    $mobile = preg_replace('/\D/', '', $mobile); // only digits

    // ✅ Fetch user first
    $res = mysqli_query(
        $conn,
        "SELECT id, mobile FROM admin WHERE mobile='$mobile' LIMIT 1"
    );

    if (mysqli_num_rows($res) == 1) {

        $user = mysqli_fetch_assoc($res);

        // ✅ Generate OTP
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        mysqli_query(
            $conn,
            "UPDATE admin 
             SET otp='$otp', otp_expires='$expiry'
             WHERE id='{$user['id']}'"
        );

        // ✅ STORE USER CONTEXT (MOST IMPORTANT)
        $_SESSION['otp_user_id'] = $user['id'];

        // ✅ TEMP DEBUG (REMOVE IN PRODUCTION)
        // $_SESSION['debug_otp'] = $otp;

        // ✅ Redirect ONLY
        header("Location: verify-otp.php");
        exit;
    } else {
        $error = "Mobile number not found";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password (OTP)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow">
                    <div class="card-body">

                        <h4 class="text-center mb-3">Forgot Password</h4>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="text"
                                name="mobile"
                                class="form-control mb-3"
                                placeholder="Enter Mobile Number"
                                required>

                            <button name="send_otp"
                                class="btn btn-dark w-100">
                                Send OTP
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="login.php">Back to Login</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>