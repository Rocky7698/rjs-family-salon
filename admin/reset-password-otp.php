<?php
session_start(); // MUST
include '../config/db.php';

/* 🔐 SESSION CHECK */
if (!isset($_SESSION['otp_user_id'])) {
    die("Session expired. Please restart password reset.");
}

$user_id = $_SESSION['otp_user_id'];
$msg = '';

if (isset($_POST['reset'])) {

    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    mysqli_query(
        $conn,
        "UPDATE admin 
         SET password='$new_password',
             otp=NULL,
             otp_expires=NULL
         WHERE id='$user_id'"
    );

    // 🔐 CLEAR SESSION (VERY IMPORTANT)
    unset($_SESSION['otp_user_id']);
    unset($_SESSION['debug_otp']);

    $msg = "Password reset successfully. <a href='login.php'>Login</a>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow">
                    <div class="card-body">

                        <h4 class="text-center mb-3">Reset Password</h4>

                        <?php if ($msg): ?>
                            <div class="alert alert-success"><?= $msg ?></div>
                        <?php endif; ?>

                        <?php if (!$msg): ?>
                            <form method="POST">

                                <div class="mb-3">
                                    <input type="password"
                                        name="new_password"
                                        class="form-control"
                                        placeholder="New Password"
                                        required>
                                </div>

                                <button name="reset"
                                    class="btn btn-dark w-100">
                                    Reset Password
                                </button>

                            </form>
                        <?php endif; ?>

                    </div>
                </div>