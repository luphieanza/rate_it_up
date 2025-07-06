<?php
session_start();
require 'koneksi.php';
$sql = "SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.id ORDER BY reviews.created_at DESC LIMIT 3";
$latest_reviews = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate it Up - Your Culinary Guide</title>
    <link rel="icon" href="assets/img/iconLogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/img/iconLogo.png" alt="Rate It Up Logo" style="height: 30px; margin-right: 10px;">
                Rate it Up
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="katalog.php">Katalog Kuliner</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section id="hero">
        <div class="container p-5 bg-danger-subtle">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            <div class="carousel-item active">
                                <img src="assets/img/gambarHero.png" class="d-block w-100"
                                    style="height: 300px; object-fit: cover;" alt="Hidangan Kuliner 1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-start mt-4 mt-md-0">
                    <img src="assets/img/iconLogo.png" alt="Rate It Up Logo"
                        style="height: 100px; margin-bottom: 15px;">
                    <h1 class="fw-bold display-4">Rate it Up</h1>
                    <h4 class="lead display-6">Find and share the best culinary spots in Indonesia!</h4>
                </div>
            </div>
        </div>
    </section>

    <section id="recommendations">
        <div class="container text-center p-5">
            <h1>Latest Reviews</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
                <?php if ($latest_reviews && $latest_reviews->num_rows > 0): ?>
                    <?php while ($row = $latest_reviews->fetch_assoc()): ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['nama_tempat']); ?></h5>
                                    <p class="card-text fst-italic">"<?php echo htmlspecialchars($row['review_text']); ?>" -
                                        @<?php echo htmlspecialchars($row['username']); ?></p>
                                    <p class="card-text">
                                        <?php for ($i = 0; $i < $row['rating']; $i++): ?>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        <?php endfor; ?>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-body-secondary">Reviewed on
                                        <?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">Belum ada review yang ditambahkan.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="text-center p-5">
        <div>
            <a href="#"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
            <a href="#"><i class="bi bi-twitter h2 p-2 text-dark"></i></a>
            <a href="#"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
        </div>
        <div>Rate it Up © 2025</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>