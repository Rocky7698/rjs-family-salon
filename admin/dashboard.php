<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/db.php';

/* ===============================
   STATUS UPDATE (SAFE HANDLING)
   =============================== */
if (isset($_GET['id'], $_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    if (in_array($status, ['Pending', 'Confirmed', 'Completed'])) {
        mysqli_query(
            $conn,
            "UPDATE appointments SET status='$status' WHERE id=$id"
        );
    }
}

/* ===============================
   DASHBOARD STATS
   =============================== */
$total = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM appointments"
))['total'];

$pending = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM appointments WHERE status='Pending'"
))['total'];

$confirmed = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM appointments WHERE status='Confirmed'"
))['total'];

$completed = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM appointments WHERE status='Completed'"
))['total'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard | RJ'S FAMILY SALON</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body {
            background: #f5f6f8;
        }

        .stat-card h3 {
            font-weight: 700;
        }

        .stat-card small {
            letter-spacing: .5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 12px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- ===============================
     NAVBAR
     =============================== -->
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand fw-bold">
            RJ'S FAMILY SALON — Admin
            <!-- <div class="text-light small">
                Logged in as: <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong>
            </div> -->
        </span>

        <span class="text-white medium d-flex align-items-center gap-1">
            <i class="bi bi-shield-check"></i>
            <?= htmlspecialchars($_SESSION['admin_username']) ?>
        </span>

        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Appointments</a>
            <a href="services.php" class="btn btn-outline-light btn-sm">Services</a>
            <a href="users.php" class="btn btn-outline-light btn-sm">Users</a>
            <a href="change-password.php" class="btn btn-outline-warning btn-sm">Password</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>

        <!-- MOBILE MENU -->
        <div class="d-md-none">
            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    Menu
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <?= htmlspecialchars($_SESSION['admin_username']) ?>
                    </li>
                    <li><a class="dropdown-item" href="dashboard.php">Appointments</a></li>
                    <li><a class="dropdown-item" href="services.php">Services</a></li>
                    <li><a class="dropdown-item" href="users.php">Users</a></li>
                    <li><a class="dropdown-item" href="change-password.php">Password</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">

        <!-- ===============================
         SUMMARY CARDS
         =============================== -->
        <div class="row mb-4 g-2">
            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">TOTAL</small>
                        <h3><?= $total ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm text-center border-warning">
                    <div class="card-body">
                        <small class="text-muted">PENDING</small>
                        <h3><?= $pending ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm text-center border-primary">
                    <div class="card-body">
                        <small class="text-muted">CONFIRMED</small>
                        <h3><?= $confirmed ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm text-center border-success">
                    <div class="card-body">
                        <small class="text-muted">COMPLETED</small>
                        <h3><?= $completed ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===============================
         APPOINTMENTS TABLE
         =============================== -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Appointments</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th style="width:150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query(
                                $conn,
                                "SELECT * FROM appointments ORDER BY id DESC"
                            );
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                                    <td><?= htmlspecialchars($row['service']) ?></td>
                                    <td><?= date("d M Y", strtotime($row['appointment_date'])) ?></td>
                                    <td><?= date("h:i A", strtotime($row['appointment_time'])) ?></td>

                                    <!-- STATUS -->
                                    <td>
                                        <?php
                                        if ($row['status'] === 'Pending') {
                                            echo '<span class="badge bg-warning text-dark">Pending</span>';
                                        } elseif ($row['status'] === 'Confirmed') {
                                            echo '<span class="badge bg-primary">Confirmed</span>';
                                        } else {
                                            echo '<span class="badge bg-success">Completed</span>';
                                        }
                                        ?>
                                    </td>

                                    <!-- ACTION -->
                                    <td>
                                        <?php if ($row['status'] === 'Pending'): ?>
                                            <a href="?id=<?= $row['id'] ?>&status=Confirmed"
                                                class="btn btn-sm btn-primary">
                                                Confirm
                                            </a>
                                        <?php elseif ($row['status'] === 'Confirmed'): ?>
                                            <a href="generate-bill.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-success">
                                                Complete & Bill
                                            </a>

                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <footer class="text-center text-muted py-3 small">
        © <?= date('Y') ?> RJ'S FAMILY SALON — Admin Panel
    </footer>

</body>

</html>