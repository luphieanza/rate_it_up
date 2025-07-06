<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kuliner - Rate it Up</title>
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="katalog.php">Katalog Kuliner</a>
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

    <div class="container mt-4">
        <h1 class="text-center mb-4">Katalog Review Kuliner</h1>
        <div id="checkin-alert-placeholder"
            style="position: fixed; top: 80px; right: 20px; z-index: 1050; min-width: 250px;"></div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="katalog-data"></div>
        <nav class="mt-5">
            <ul class="pagination justify-content-center" id="pagination-links"></ul>
        </nav>
    </div>

    <footer class="text-center p-4 mt-5 bg-light">
        Rate it Up © 2025
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>

        $(document).ready(function () {
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

            function loadKatalog(page) {
                $.ajax({
                    url: 'data_katalog.php',
                    type: 'GET',
                    data: { page: page },
                    dataType: 'json',
                    success: function (response) {
                        $('#katalog-data').empty();
                        $('#pagination-links').empty();
                        if (response.reviews && response.reviews.length > 0) {
                            $.each(response.reviews, function (index, review) {
                                let stars = '<span>'.repeat(review.rating).replaceAll('<span>', '<i class="bi bi-star-fill text-warning"></i>');
                                let checkinButton = isLoggedIn ? `<button class="btn btn-sm btn-outline-primary float-end check-in-btn" data-place="${review.nama_tempat}"><i class="bi bi-geo-alt-fill"></i> Check-in</button>` : '';
                                let reviewCard = `
                            <div class="col"><div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">${review.nama_tempat}</h5>
                                    <p class="card-text fst-italic">"${review.review_text}"</p>
                                    <p class="card-text">${stars}</p>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <small class="text-muted">Diulas oleh: @${review.username}</small>
                                    ${checkinButton}
                                </div>
                            </div></div>`;
                                $('#katalog-data').append(reviewCard);
                            });
                        } else {
                            $('#katalog-data').html('<div class="col-12"><p class="text-center">Belum ada review untuk ditampilkan.</p></div>');
                        }
                        if (response.total_pages) {
                            for (let i = 1; i <= response.total_pages; i++) {
                                let pageClass = (i == page) ? 'page-item active' : 'page-item';
                                let pageLink = `<li class="${pageClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                                $('#pagination-links').append(pageLink);
                            }
                        }
                    }
                });
            }
            loadKatalog(1);
            $(document).on('click', '.page-link', function (e) { e.preventDefault(); loadKatalog($(this).data('page')); });
            $(document).on('click', '.check-in-btn', function () {
                let placeName = $(this).data('place');
                let button = $(this);

                $.ajax({
                    url: 'check_in_action.php',
                    type: 'POST',
                    data: { place_name: placeName },
                    dataType: 'json',
                    beforeSend: function () {
                        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    },
                    success: function (response) {
                        let alertType = response.status === 'success' ? 'success' : 'danger';
                        let alertHTML = `<div class="alert alert-${alertType} alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#checkin-alert-placeholder').html(alertHTML);

                        if (response.status === 'success') {
                            button.removeClass('btn-outline-primary').addClass('btn-success')
                                .html('<i class="bi bi-check-circle-fill"></i> Checked-in');
                        } else {
                            button.prop('disabled', false).html('<i class="bi bi-geo-alt-fill"></i> Check-in');
                        }
                    },
                    error: function () {
                        button.prop('disabled', false).html('<i class="bi bi-geo-alt-fill"></i> Check-in');
                        let alertHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Gagal terhubung ke server.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#checkin-alert-placeholder').html(alertHTML);
                    }
                });
            });
        });
    </script>
</body>

</html>