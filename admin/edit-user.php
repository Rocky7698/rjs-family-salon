<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include '../config/db.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int)$_GET['id'];
$msg = '';
$error = '';

// Fetch user
$res = mysqli_query(
    $conn,
    "SELECT id, username, mobile FROM admin WHERE id='$id' LIMIT 1"
);

if (mysqli_num_rows($res) != 1) {
    die("User not found");
}

$user = mysqli_fetch_assoc($res);

/* UPDATE USER */
if (isset($_POST['update'])) {

    $username = trim($_POST['username']);
    $mobile   = trim($_POST['mobile']);
    $mobile   = preg_replace('/\D/', '', $mobile);

    if (strlen($mobile) != 10) {
        $error = "Mobile number must be 10 digits";
    } else {

        // Check duplicate username (exclude current user)
        $check = mysqli_query(
            $conn,
            "SELECT id FROM admin 
             WHERE username='$username' 
             AND id!='$id'
             LIMIT 1"
        );

        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists";
        } else {

            mysqli_query(
                $conn,
                "UPDATE admin 
                 SET username='$username',
                     mobile='$mobile'
                 WHERE id='$id'"
            );

            // Update session username if self edited
            if ($_SESSION['admin_username'] == $user['username']) {
                $_SESSION['admin_username'] = $username;
            }

            $msg = "User updated successfully";
            $user['username'] = $username;
            $user['mobile']   = $mobile;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Admin User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Edit Admin User</span>
            <a href="users.php" class="btn btn-outline-light btn-sm">Back</a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <?php if ($msg): ?>
                    <div class="alert alert-success"><?= $msg ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-body">

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text"
                                    name="username"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['username']) ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text"
                                    name="mobile"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['mobile']) ?>"
                                    required>
                            </div>

                            <button name="update"
                                class="btn btn-dark w-100">
                                Update User
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>