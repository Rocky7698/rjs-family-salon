<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/db.php';

/* =====================
   ADD SERVICE
   ===================== */
if (isset($_POST['add'])) {
    $category = $_POST['category'];
    $name = $_POST['service_name'];
    $price = $_POST['price'];

    mysqli_query(
        $conn,
        "INSERT INTO services (category, service_name, price)
         VALUES ('$category','$name','$price')"
    );

    header("Location: services.php");
    exit;
}

/* =====================
   UPDATE SERVICE
   ===================== */
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $name = $_POST['service_name'];
    $price = $_POST['price'];

    mysqli_query(
        $conn,
        "UPDATE services 
         SET category='$category',
             service_name='$name',
             price='$price'
         WHERE id=$id"
    );

    header("Location: services.php");
    exit;
}

/* =====================
   DELETE SERVICE
   ===================== */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM services WHERE id=$id");
    header("Location: services.php");
    exit;
}

/* =====================
   EDIT MODE DATA
   ===================== */
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM services WHERE id=$id");
    $editData = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Services | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Manage Services</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Back</a>
        </div>
    </nav>

    <div class="container my-4">

        <!-- ADD / EDIT FORM -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">
                    <?= $editData ? 'Edit Service' : 'Add New Service' ?>
                </h5>

                <form method="POST" class="row g-3">

                    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <input type="text" name="category"
                            class="form-control"
                            required
                            value="<?= $editData['category'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="service_name"
                            class="form-control"
                            required
                            value="<?= $editData['service_name'] ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <input type="text" name="price"
                            class="form-control"
                            required
                            value="<?= $editData['price'] ?? '' ?>">
                    </div>

                    <div class="col-md-2 d-grid">
                        <?php if ($editData) { ?>
                            <button name="update" class="btn btn-primary mt-4">
                                Update
                            </button>
                        <?php } else { ?>
                            <button name="add" class="btn btn-dark mt-4">
                                Add
                            </button>
                        <?php } ?>
                    </div>

                </form>
            </div>
        </div>

        <!-- SERVICES LIST -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Services List</h5>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Category</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th style="width:150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
                            while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                                <tr>
                                    <td><?= $row['category'] ?></td>
                                    <td><?= $row['service_name'] ?></td>
                                    <td><?= $row['price'] ?></td>
                                    <td>
                                        <a href="?edit=<?= $row['id'] ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <a href="?delete=<?= $row['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this service?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</body>

</html>