<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include '../config/db.php';

$username = $_SESSION['admin_username'];
$message = '';
$error = '';

if (isset($_POST['change'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Get current user password hash
    $result = mysqli_query(
        $conn,
        "SELECT password FROM admin WHERE username='$username'"
    );
    $row = mysqli_fetch_assoc($result);

    // Verify current password
    if (!password_verify($current_password, $row['password'])) {
        $error = "Current password is incorrect";
    } else {
        // Update new password
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        mysqli_query(
            $conn,
            "UPDATE admin SET password='$new_hash' WHERE username='$username'"
        );

        $message = "Password changed successfully";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">
                Change Password
                <small class="text-light ms-2">
                    (<?= htmlspecialchars($username) ?>)
                </small>
            </span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Back</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow">
                    <div class="card-body">

                        <h4 class="text-center mb-3">Change Your Password</h4>

                        <?php if ($message): ?>
                            <div class="alert alert-success"><?= $message ?></div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password"
                                    name="current_password"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password"
                                    name="new_password"
                                    class="form-control"
                                    required>
                            </div>

                            <button name="change"
                                class="btn btn-dark w-100">
                                Update Password
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>