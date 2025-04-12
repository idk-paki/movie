<?php
session_start();
require 'dataconnection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request: User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$user_stmt = $connect->prepare("SELECT user_name, user_email FROM `user` WHERE user_id = ?");
if (!$user_stmt) {
    error_log("Failed to prepare user statement: " . $connect->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$user_stmt->bind_param("i", $user_id);
if (!$user_stmt->execute()) {
    error_log("Failed to execute user statement: " . $user_stmt->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$user_result = $user_stmt->get_result();
if ($user_result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

$user_data = $user_result->fetch_assoc();
$user_name = $user_data['user_name'];
$user_email = $user_data['user_email'];

// Get all videos in the cart and calculate total_pay
$cart_stmt = $connect->prepare("SELECT video_id, seat_number FROM cart WHERE user_id = ?");
if (!$cart_stmt) {
    error_log("Failed to prepare cart statement: " . $connect->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$cart_stmt->bind_param("i", $user_id);
if (!$cart_stmt->execute()) {
    error_log("Failed to execute cart statement: " . $cart_stmt->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$cart_result = $cart_stmt->get_result();
if ($cart_result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Cart is empty"]);
    exit;
}

$total_pay = 0;
$videos = [];

while ($cart_row = $cart_result->fetch_assoc()) {
    $video_id = $cart_row['video_id'];
    $seat_number = $cart_row['seat_number'];

    $video_stmt = $connect->prepare("SELECT video_price FROM video WHERE video_id = ?");
    if (!$video_stmt) {
        error_log("Failed to prepare video statement: " . $connect->error);
        echo json_encode(["status" => "error", "message" => "Database error"]);
        exit;
    }

    $video_stmt->bind_param("i", $video_id);
    if (!$video_stmt->execute()) {
        error_log("Failed to execute video statement: " . $video_stmt->error);
        echo json_encode(["status" => "error", "message" => "Database error"]);
        exit;
    }

    $video_result = $video_stmt->get_result();
    if ($video_result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Video not found"]);
        exit;
    }

    $video_price = $video_result->fetch_assoc()['video_price'];
    $total_pay += $video_price;
    $videos[] = [
        "video_id" => $video_id, 
        "video_price" => $video_price, 
        "seat_number" => $seat_number
    ];
}

// Insert data into the database
$purchased_stmt = $connect->prepare("INSERT INTO purchased(user_id, video_id, total_pay) VALUES (?, ?, ?)");
if (!$purchased_stmt) {
    error_log("Failed to prepare purchased statement: " . $connect->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$purchased_stmt->bind_param("iid", $user_id, $video_id, $total_pay);
if (!$purchased_stmt->execute()) {
    error_log("Failed to execute purchased statement: " . $purchased_stmt->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$purchased_id = $purchased_stmt->insert_id;
if (!$purchased_id) {
    error_log("Failed to get purchased ID");
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

// Insert order_item and seat records
foreach ($videos as $video) {
    $video_id = $video['video_id'];
    $video_price = $video['video_price'];
    $seat_number = $video['seat_number'];

    // Insert order_item record
    $order_stmt = $connect->prepare("INSERT INTO order_item(user_id, purchased_id, seat_number, video_id, video_price) VALUES (?, ?, ?, ?, ?)");
    if (!$order_stmt) {
        error_log("Failed to prepare order_item statement: " . $connect->error);
        echo json_encode(["status" => "error", "message" => "Database error"]);
        exit;
    }

    $order_stmt->bind_param("iisid", $user_id, $purchased_id, $seat_number, $video_id, $video_price);
    if (!$order_stmt->execute()) {
        error_log("Failed to execute order_item statement: " . $order_stmt->error);
        echo json_encode(["status" => "error", "message" => "Database error"]);
        exit;
    }

    // Insert seat record
    $seat_stmt = $connect->prepare("INSERT INTO seat(seat_number, video_id, user_id, purchased_id) VALUES (?, ?, ?, ?)");
    if (!$seat_stmt) {
        error_log("Failed to prepare seat statement: " . $connect->error);
        echo json_encode(["status" => "error", "message" => "Database error"]);
        exit;
    }

    $seat_stmt->bind_param("siii", $seat_number, $video_id, $user_id, $purchased_id);
    if (!$seat_stmt->execute()) {
        error_log("Failed to execute seat statement: " . $seat_stmt->error);
        echo json_encode(["status" => "error", "message" => "Database error"]);
        exit;
    }
}

// Clear cart
$delete_cart_stmt = $connect->prepare("DELETE FROM cart WHERE user_id = ?");
if (!$delete_cart_stmt) {
    error_log("Failed to prepare delete cart statement: " . $connect->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$delete_cart_stmt->bind_param("i", $user_id);
if (!$delete_cart_stmt->execute()) {
    error_log("Failed to execute delete cart statement: " . $delete_cart_stmt->error);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

// Send confirmation email
require 'assets/includes/PHPMailer.php';
require 'assets/includes/SMTP.php';
require 'assets/includes/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "thisisemail12980@gmail.com";
$mail->Password = "osfgrvxbrdsbicpd"; // Use Gmail app password
$mail->setFrom("thisisemail12980@gmail.com");
$mail->addAddress($user_email);
$mail->isHTML(true);
$mail->Subject = "Purchase Successful On Lex Movie!";
$mail->Body = '<div class=""><div class="aHl"></div><div id=":17g" tabindex="-1"></div><div id=":175" class="ii gt" jslog="20277; u014N:xr6bB; 4:W251bGwsbnVsbCxbXV0."><div id=":174" class="a3s aiL msg-4165883047653871377"><u></u>

	 <div marginwidth="0" marginheight="0" style="font:14px/20px "Helvetica",Arial,sans-serif;margin:0;padding:75px 0 0 0;text-align:center;background-color:#eeeeee">
			<center>
				<table border="0" cellpadding="20" cellspacing="0" height="100%" width="100%" id="m_-4165883047653871377bodyTable" style="background-color:#eeeeee">
					<tbody><tr>
						<td align="center" valign="top">
							
							
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;border-radius:6px;background-color:none" id="m_-4165883047653871377templateContainer" class="m_-4165883047653871377rounded6">
								<tbody><tr>
									<td align="center" valign="top">
										
										<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px">
											<tbody><tr>
												<td>
													<h1 style="font-size:28px;line-height:110%;margin-bottom:30px;margin-top:0;padding:0">
	Lex Movie</h1>
												</td>
											</tr>
										</tbody></table>
										
									</td>
								</tr>
								<tr>
									<td align="center" valign="top">
										
										<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;border-radius:6px;background-color:#ffffff" id="m_-4165883047653871377templateBody" class="m_-4165883047653871377rounded6">
											<tbody><tr>
												
												<td align="left" valign="top" class="m_-4165883047653871377bodyContent" style="line-height:150%;font-family:Helvetica;font-size:14px;color:#333333;padding:20px">
													
													<h2 style="font-size:22px;line-height:28px;margin:0 0 12px 0">Payment <span class="il">Successful</span> 
	</h2>
	<br/>
	<b>You have buy an Movie Ticket From Lex Movie<b>
	<br><br>
	<p>you can download the payment Receipt at below link</p>

	 <br/> 
	<a href="http://localhost/Lex_movie/user/makepdf.php?pdf&id=' . urlencode($user_id) . '&purid=' . urlencode($purchased_id) . '">

	<input style="width: 25%;
				height: 40px;
				color: #fff;
				background: #0b8793;
				font-weight: bold;
				outline: none;
				border: none;
				border-radius: 25px;
				padding:5px;"
				 class="button" type="button" value="Download Receipt"></a>
	<br>
	<div><p style="padding:0 0 10px 0">If you received this <span class="il">email</span> by mistake, simply delete it. You wont be subscribed if you dont click the confirmation link above.</p>
	<p style="padding:0 0 10px 0">For questions about this list, please contact:
	<br><a href="iiustudentservices@newinti.edu.my" style="color:#336699" target="_blank">iiustudentservices@newinti.edu.my</a></p>
	</div>


	<span>
	  <span content="We need to confirm your email address."></span>
	  <span>
		
		<span>
		  
		  
		</span>
	  </span>
	</span>


												</td>
												
											</tr>
										</tbody></table>
										
									</td>
								</tr>
								<tr>
									<td align="center" valign="top">
										
										<table border="0" cellpadding="20" cellspacing="0" width="100%" style="max-width:600px">
											<tbody><tr>
												<td align="center" valign="top">
													
												   
													
												</td>
											</tr>
										</tbody></table>
										
									</td>
								</tr>
							</tbody></table>
							
							
						</td>
					</tr>
				</tbody></table>
			</center>
		   </div><div class="yj6qo"></div><div class="adL">
			</div>
	</div>
	</div>
		<div id=":17k" class="ii gt" style="display:none">
			<div id=":17l" class="a3s aiL ">
			</div>
		</div>
		<div class="hi">
		</div>
	</div>';

if ($mail->send()) {
    echo json_encode(["status" => "success", "message" => "Payment successful and email sent", "purchased_id" => $purchased_id]);
} else {
    error_log("Failed to send email: " . $mail->ErrorInfo);
    echo json_encode(["status" => "error", "message" => "Payment successful but email failed"]);
}

exit;
?>