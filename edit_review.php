<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header('Location: dashboard.php');
    exit;
}

$review_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_tempat = $_POST['nama_tempat'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    $stmt = $conn->prepare("UPDATE reviews SET nama_tempat = ?, review_text = ?, rating = ? WHERE id = ?");
    $stmt->bind_param("ssii", $nama_tempat, $review_text, $rating, $review_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $review = $result->fetch_assoc();
} else {
    echo "Review tidak ditemukan.";
    header('Refresh: 3; URL=dashboard.php');
    exit;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review - Rate it Up</title>
    <link rel="icon" href="assets/img/iconLogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Edit Review Kuliner</h3>
                    </div>
                    <div class="card-body">
                        <form action="edit_review.php?id=<?php echo $review_id; ?>" method="POST">
                            <div class="mb-3">
                                <label for="nama_tempat" class="form-label">Nama Tempat</label>
                                <input type="text" class="form-control" id="nama_tempat" name="nama_tempat"
                                    value="<?php echo htmlspecialchars($review['nama_tempat']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="review_text" class="form-label">Review Anda</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="4"
                                    required><?php echo htmlspecialchars($review['review_text']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating (1-5)</label>
                                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5"
                                    value="<?php echo htmlspecialchars($review['rating']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Review</button>
                            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>