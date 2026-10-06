<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include '../config/db.php';
$msg = '';
$error = '';

/* ADD USER */
if (isset($_POST['add'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $mobile   = trim($_POST['mobile']);

    // sanitize mobile
    $mobile = preg_replace('/\D/', '', $mobile);

    if (strlen($mobile) != 10) {
        $error = "Mobile number must be 10 digits";
    } else {

        // check duplicate username
        $check = mysqli_query(
            $conn,
            "SELECT id FROM admin WHERE username='$username' LIMIT 1"
        );

        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query(
                $conn,
                "INSERT INTO admin (username, password, mobile)
                 VALUES ('$username','$hash','$mobile')"
            );

            $msg = "New admin user added successfully";
        }
    }
}

/* DELETE USER */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // prevent self-delete
    $self = mysqli_query(
        $conn,
        "SELECT id FROM admin WHERE username='{$_SESSION['admin_username']}'"
    );
    $me = mysqli_fetch_assoc($self);

    if ($me['id'] != $id) {
        mysqli_query($conn, "DELETE FROM admin WHERE id=$id");
    }

    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Users</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Back</a>
        </div>
    </nav>

    <div class="container my-4">

        <?php if ($msg): ?>
            <div class="alert alert-success"><?= $msg ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- ADD USER FORM -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Add New Admin</h5>

                <form method="POST" class="row g-3">
                    <div class="col-md-3">
                        <input type="text"
                            name="username"
                            class="form-control"
                            placeholder="Username"
                            required>
                    </div>

                    <div class="col-md-3">
                        <input type="password"
                            name="password"
                            class="form-control"
                            placeholder="Password"
                            required>
                    </div>

                    <div class="col-md-3">
                        <input type="text"
                            name="mobile"
                            class="form-control"
                            placeholder="Mobile (10 digits)"
                            required>
                    </div>

                    <div class="col-md-2">
                        <button name="add"
                            class="btn btn-dark w-100">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- USERS LIST -->
        <div class="card">
            <div class="card-body">
                <h5>Admin Users List</h5>

                <table class="table table-bordered align-middle">
                    <tr>
                        <th>Username</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    $res = mysqli_query($conn, "SELECT id, username, mobile FROM admin");
                    while ($row = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['mobile']) ?></td>
                            <td>
                                <a href="edit-user.php?id=<?= $row['id'] ?>"
                                    class="btn btn-sm btn-primary">
                                    Edit
                                </a>

                                <a href="?delete=<?= $row['id'] ?>"
                                    onclick="return confirm('Delete this user?')"
                                    class="btn btn-sm btn-danger">
                                    Delete
                                </a>
                            </td>

                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

    </div>

</body>

</html>