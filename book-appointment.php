<?php
include 'config/db.php';

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $date = $_POST['date'];

    // 12 hour time with AM/PM
    $time = $_POST['hour'] . ':' . $_POST['minute'] . ' ' . $_POST['ampm'];

    mysqli_query(
        $conn,
        "INSERT INTO appointments 
        (name, mobile, service, appointment_date, appointment_time)
        VALUES 
        ('$name','$mobile','$service','$date','$time')"
    );

    header("Location: thank-you.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Appointment | RJ'S FAMILY SALON</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">RJ'S FAMILY SALON</a>
        </div>
    </nav>

    <!-- Booking Form -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">

                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="assets/images/logo.png"
                                style="max-width:100px;height:auto;display:block;margin:auto;">

                            <h5 class="fw-bold mb-0">RJ'S FAMILY SALON</h5>
                        </div>

                        <h3 class="text-center mb-4">Book Appointment</h3>


                        <form method="POST">

                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Your Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" required>
                            </div>

                            <!-- Service -->
                            <div class="mb-3">
                                <label class="form-label">Select Service</label>
                                <select name="service" class="form-select" required>
                                    <option value="">Select Service</option>
                                    <?php
                                    $services = mysqli_query($conn, "SELECT * FROM services");
                                    while ($row = mysqli_fetch_assoc($services)) {
                                        echo "<option value='{$row['service_name']}'>
                                            {$row['service_name']} ({$row['price']})
                                          </option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="mb-3">
                                <label class="form-label">Preferred Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>

                            <!-- Time (12 Hour AM/PM) -->
                            <div class="mb-4">
                                <label class="form-label">Preferred Time</label>

                                <div class="d-flex gap-2">
                                    <select name="hour" class="form-select" required>
                                        <option value="">Hour</option>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>

                                    <select name="minute" class="form-select" required>
                                        <option value="">Min</option>
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                    </select>

                                    <select name="ampm" class="form-select" required>
                                        <option value="">AM/PM</option>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" name="submit" class="btn btn-dark w-100">
                                Book Appointment
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>