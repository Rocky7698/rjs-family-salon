<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../config/db.php';

if (isset($_POST['upload'])) {

    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    // correct path for database (frontend readable)
    $filename = time() . "_" . $img;
    $db_path = "assets/images/slider/" . $filename;
    $server_path = "../assets/images/slider/" . $filename;

    move_uploaded_file($tmp, $server_path);

    mysqli_query($conn, "INSERT INTO sliders (image) VALUES ('$db_path')");
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Slider</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container my-4">
        <h3>Home Slider Images</h3>

        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <input type="file" name="image" class="form-control mb-2" required>
            <button name="upload" class="btn btn-dark">Upload Image</button>
        </form>

        <div class="row">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM sliders ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($res)) {
            ?>
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="../<?= $row['image'] ?>" class="card-img-top">
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</body>

</html>