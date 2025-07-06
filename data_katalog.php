<?php
require 'koneksi.php';

$limit = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page > 1) ? ($page - 1) * $limit : 0;

$total_result = $conn->query("SELECT COUNT(*) as total FROM reviews");
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT reviews.*, users.username 
        FROM reviews 
        JOIN users ON reviews.user_id = users.id 
        ORDER BY reviews.created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

$response = [
    'reviews' => $reviews,
    'total_pages' => $total_pages,
    'current_page' => $page
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>