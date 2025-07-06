<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_tempat = $_POST['nama_tempat'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, nama_tempat, review_text, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $nama_tempat, $review_text, $rating);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Review - Rate it Up</title>
    <link rel="icon" href="assets\img\iconLogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Tambah Review Kuliner Baru</h3>
                    </div>
                    <div class="card-body">
                        <form action="tambah_review.php" method="POST">
                            <div class="mb-3">
                                <label for="nama_tempat" class="form-label">Nama Tempat</label>
                                <input type="text" class="form-control" id="nama_tempat" name="nama_tempat" required>
                            </div>
                            <div class="mb-3">
                                <label for="review_text" class="form-label">Review Anda</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="4"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating (1-5)</label>
                                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Review</button>
                            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>