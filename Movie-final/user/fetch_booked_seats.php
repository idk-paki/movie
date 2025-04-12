<?php
include("dataconnection.php");
session_start();
header("Content-Type: application/json");

$video_id = $_POST['video_id'] ?? null;

if (!$video_id) {
    echo json_encode(["error" => "No video ID provided"]);
    exit;
}

$query = "SELECT seat_number FROM seat WHERE video_id = ? AND user_id IS NOT NULL";
$stmt = $connect->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "SQL error"]);
    exit;
}

$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();

$bookedSeats = [];
while ($row = $result->fetch_assoc()) {
    $bookedSeats[] = $row['seat_number'];
}

$stmt->close();
$connect->close();

echo json_encode($bookedSeats);
?>
