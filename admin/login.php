<?php
session_start();
include '../config/db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_username'] = $row['username'];
        header("Location: dashboard.php");

        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Login | RJ'S FAMILY SALON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* =====================
   ADMIN LOGIN LOGO
   ===================== */

        .admin-logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .admin-logo {
            max-width: 110px;
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Mobile fine-tune */
        @media (max-width: 768px) {
            .admin-logo {
                max-width: 90px;
            }
        }
    </style>

</head>

<body class="bg-light">

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-3">
                <div class="admin-logo-wrap">
                    <img src="../assets/images/logo.png"
                        alt="RJ'S Family Salon Logo"
                        class="admin-logo">
                </div>
                <h5 class="fw-bold mb-0">RJ'S FAMILY SALON</h5>
            </div>

            <h4 class="text-center mb-3">Admin Login</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button name="login" class="btn btn-dark w-100">Login</button>
                <div class="text-center mt-2">
                    <a href="forgot-password-otp.php">Forgot Password (OTP)</a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>