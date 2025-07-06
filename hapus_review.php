<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_GET['id'])) {
    $review_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: Gagal menghapus data.";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: dashboard.php');
    exit;
}
?>