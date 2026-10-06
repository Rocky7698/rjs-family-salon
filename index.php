<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>RJ'S FAMILY SALON | Navsari</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">


</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php">
                <img src="assets/images/logo.png" alt="RJ'S Family Salon Logo" class="navbar-logo">
                <span>RJ'S FAMILY SALON</span>
            </a>


            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="book-appointment.php">Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SLIDER -->
    <section class="hero p-0">
        <div id="salonSlider"
            class="carousel slide"
            data-bs-ride="carousel"
            data-bs-interval="2500">

            <div class="carousel-inner">

                <?php
                $slider = mysqli_query($conn, "SELECT * FROM sliders WHERE status = 1 ORDER BY id DESC");
                $i = 0;
                while ($row = mysqli_fetch_assoc($slider)) {
                ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                        <div class="hero-slide"
                            style="background-image:url('<?= $row['image'] ?>')">
                            <div class="overlay">
                                <h1>RJ'S FAMILY SALON</h1>
                                <p>Premium Family Salon in Navsari</p>

                                <p class="mt-2">
                                    ⭐⭐⭐⭐⭐ 5.0 Rating | 64+ Google Reviews
                                </p>

                                <a href="book-appointment.php" class="btn btn-salon mt-2">
                                    Book Appointment
                                </a>
                            </div>
                        </div>
                    </div>
                <?php $i++;
                } ?>

            </div>
            <!-- Left Arrow -->
            <button class="carousel-control-prev"
                type="button"
                data-bs-target="#salonSlider"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <!-- Right Arrow -->
            <button class="carousel-control-next"
                type="button"
                data-bs-target="#salonSlider"
                data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </section>

    <!-- SERVICES -->
    <section class="py-5" id="services">
        <div class="container">

            <h2 class="text-center section-title mb-5">Our Services</h2>

            <div class="row justify-content-center">
                <?php
                $services = mysqli_query($conn, "SELECT * FROM services");
                while ($row = mysqli_fetch_assoc($services)) {
                ?>
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-body text-center">
                                <div class="mb-3 fs-1">💇‍♀️</div>
                                <h5 class="card-title"><?= $row['service_name'] ?></h5>
                                <p class="text-muted"><?= $row['category'] ?></p>
                                <p class="fw-bold"><?= $row['price'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </section>

    <!-- CONTACT -->
    <section class="bg-light py-5" id="contact">
        <div class="container text-center">
            <h2 class="section-title">Contact Us</h2>

            <p>
                KK Plaza, near Prajapati Ashram,<br>
                Commercio, Kadiyawad,<br>
                Navsari – 396445
            </p>

            <p><strong>Phone:</strong> 087349 29224</p>
            <p><strong>Timings:</strong> 9:00 AM – 8:00 PM (All Days)</p>

            <a href="tel:08734929224" class="btn btn-dark">Call Now</a>
            <a href="book-appointment.php" class="btn btn-salon ms-2">Book Online</a>
        </div>
    </section>

    <!-- FLOATING BUTTONS -->
    <div class="floating-buttons">
        <a href="https://wa.me/918734929224?text=Hello%20RJ%27s%20Family%20Salon,%20I%20want%20to%20book%20an%20appointment"
            target="_blank"
            class="whatsapp-btn">
            <i class="fab fa-whatsapp"></i>
        </a>

        <a href="tel:08734929224" class="call-btn">
            <i class="fas fa-phone"></i>
        </a>
    </div>

    <!-- FOOTER -->
    <div class="mb-3">
        <a href="https://www.instagram.com/CLIENT_USERNAME"
            target="_blank"
            class="social-icon">
            <i class="fab fa-instagram"></i>
        </a>

        <a href="https://www.facebook.com/CLIENT_PAGE"
            target="_blank"
            class="social-icon">
            <i class="fab fa-facebook-f"></i>
        </a>

        <a href="https://g.page/CLIENT_GOOGLE_REVIEW"
            target="_blank"
            class="social-icon">
            <i class="fas fa-map-marker-alt"></i>
        </a>
    </div>
    <footer class="bg-dark text-white text-center py-3">


        © <?= date('Y'); ?> RJ'S FAMILY SALON | Navsari
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('#services .card').forEach(card => {
            card.onclick = () => {
                window.location.href = 'book-appointment.php';
            };
        });
    </script>
</body>

</html>