<!DOCTYPE html>
<html lang="en">

<head>
    <title>Appointment Booked | RJ'S FAMILY SALON</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f5f5f5;
        }

        .success-box {
            background: #fff;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 70px;
            color: #28a745;
        }

        .btn-salon {
            background: linear-gradient(135deg, #c59d5f, #b0894f);
            color: #fff;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
        }

        .btn-salon:hover {
            color: #fff;
            background: linear-gradient(135deg, #b0894f, #a0783f);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">RJ'S FAMILY SALON</a>
        </div>
    </nav>

    <!-- Thank You Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">

                <div class="success-box">

                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <h3 class="mb-3">Appointment Request Sent!</h3>

                    <p class="text-muted mb-4">
                        Thank you for choosing <strong>RJ'S Family Salon</strong>.<br>
                        Your appointment request has been received successfully.
                    </p>

                    <div class="alert alert-info mb-4">
                        Our team will <strong>call or WhatsApp you shortly</strong>
                        to confirm your appointment.
                    </div>

                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="index.php" class="btn btn-outline-dark">
                            Back to Home
                        </a>

                        <a href="https://wa.me/918734929224"
                            target="_blank"
                            class="btn btn-salon">
                            <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                        </a>
                    </div>

                    <hr class="my-4">

                    <p class="small text-muted mb-0">
                        📍 KK Plaza, near Prajapati Ashram, Navsari<br>
                        🕘 9:00 AM – 8:00 PM (All Days)
                    </p>

                </div>

            </div>
        </div>
    </div>

</body>

</html>