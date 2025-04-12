<?php
include("dataconnection.php");
session_start();

if ($connect->connect_error) {
    die("Database connection failed: " . $connect->connect_error);
}


$seat_numbers = $_POST['seat_number'] ?? null;  
$video_id = $_POST['video_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;

if (!$seat_numbers || !$video_id || !$user_id) {
    die("Error: Missing required fields.");
}


$seat_array = explode(",", $seat_numbers);

foreach ($seat_array as $seat_number) {
    // check seat already buy or not
    $check_stmt = $connect->prepare("SELECT * FROM seat WHERE seat_number = ? AND video_id = ?");
    
    if (!$check_stmt) {
        die("Prepare failed (check): " . $connect->error);
    }

    $check_stmt->bind_param("si", $seat_number, $video_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Error: seat $seat_number already buy！";
        exit;
    }
	
    $stmt = $connect->prepare("INSERT INTO cart (seat_number, video_id, user_id) VALUES (?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed (insert): " . $connect->error); 
    }

    $stmt->bind_param("sii", $seat_number, $video_id, $user_id);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        exit;
    }
	

	
}

echo "Success";

$stmt->close();
$connect->close();
?>
