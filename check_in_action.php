<?php
session_start();
require 'koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk melakukan check-in.']);
    exit;
}

if (!isset($_POST['place_name']) || empty($_POST['place_name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nama tempat tidak valid.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$place_name = $_POST['place_name'];

$stmt = $conn->prepare("INSERT INTO check_ins (user_id, place_name) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $place_name);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Berhasil check-in di ' . htmlspecialchars($place_name) . '!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan check-in. Mungkin Anda sudah pernah check-in di sini.']);
}

$stmt->close();
$conn->close();
?>