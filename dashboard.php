<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rate it Up</title>
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
                        <a class="nav-link" href="katalog.php">Katalog Kuliner</a>
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Daftar Semua Review</h2>
            <?php if ($_SESSION['role'] === 'administrator'): ?>
                <a href="tambah_review.php" class="btn btn-primary">Tambah Review Baru</a>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Nama Tempat</th>
                        <th>Review</th>
                        <th>Rating</th>
                        <th>Diulas oleh</th>
                        <?php if ($_SESSION['role'] === 'administrator'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="review-data">
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination justify-content-center" id="pagination-links"></ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const userRole = '<?php echo $_SESSION['role']; ?>';

        $(document).ready(function () {
            function loadReviews(page) {
                $.ajax({
                    url: 'data_reviews.php',
                    type: 'GET',
                    data: { page: page },
                    dataType: 'json',
                    success: function (response) {
                        $('#review-data').empty();
                        $('#pagination-links').empty();
                        let offset = (page - 1) * 5;
                        if (response.reviews && response.reviews.length > 0) {
                            $.each(response.reviews, function (index, review) {
                                let actionButtons = '';
                                if (userRole === 'administrator') {
                                    actionButtons = `
                                <td>
                                    <a href="edit_review.php?id=${review.id}" class="btn btn-success btn-sm">Edit</a>
                                    <a href="hapus_review.php?id=${review.id}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?');">Delete</a>
                                </td>`;
                                }

                                let reviewRow = `<tr>
                                <td>${offset + index + 1}</td>
                                <td>${review.nama_tempat}</td>
                                <td>${review.review_text}</td>
                                <td>${review.rating} <i class="bi bi-star-fill text-warning"></i></td>
                                <td>@${review.username}</td>
                                ${actionButtons} 
                            </tr>`;
                                $('#review-data').append(reviewRow);
                            });
                        } else {
                            $('#review-data').append(`<tr><td colspan="${userRole === 'administrator' ? '6' : '5'}" class="text-center">Belum ada review.</td></tr>`);
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
            loadReviews(1);
            $(document).on('click', '.page-link', function (e) { e.preventDefault(); loadReviews($(this).data('page')); });
        });
    </script>
</body>

</html>