<?php
session_start();
require 'dataconnection.php';

if (!isset($_GET['vid']) || !isset($_SESSION['user_id'])) {
    die("Invalid access");
}

$video_id = htmlspecialchars($_GET['vid']);
$user_id = $_SESSION['user_id'];

if (!isset($connect)) {
    die("Database connection failed!");
}

// get user info
$user_stmt = $connect->prepare("SELECT user_name, user_email FROM `user` WHERE user_id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    die("User not found");
}
$user_data = $user_result->fetch_assoc();
$user_name = $user_data['user_name'];
$user_email = $user_data['user_email'];

// get cart info
$cart_stmt = $connect->prepare("SELECT seat_number, video_id, (SELECT video_price FROM video WHERE video.video_id = cart.video_id) as video_price FROM cart WHERE user_id = ? AND video_id = ?");
$cart_stmt->bind_param("ii", $user_id, $video_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$total_price = 0;
$seats = [];
while ($row = $cart_result->fetch_assoc()) {
    $seats[] = $row;
    $total_price += $row['video_price'];
}

// cancel and clean cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_purchase'])) {
    $delete_cart_stmt = $connect->prepare("DELETE FROM cart WHERE user_id = ? AND video_id = ?");
    $delete_cart_stmt->bind_param("ii", $user_id, $video_id);
    if ($delete_cart_stmt->execute()) {
        echo "<script>alert('Cancel Order！'); window.location.href='movie-details.php?vid={$video_id}';</script>";
        exit;
    } else {
        echo "<script>alert('Cant cancel, please retry！');</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f8f9fa; }
        .container { display: flex; justify-content: center; gap: 20px; max-width: 900px; margin: auto; }
        .box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); flex: 1; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .btn { display: block; width: 100%; padding: 12px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn-primary { background-color: #04AA6D; color: white; }
        .btn-primary:hover { background-color: #45a049; }
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-danger:hover { background-color: #c0392b; }
        input { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .error { color: red; display: none; }
    </style>
</head>
<body>

<style>
body{
	background: url("assets/img/images2.avif");
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
	object-fit: cover;
}
</style>

<h2>Checkout</h2>
<div class="container">
    <!-- Purchase Details -->
    <div class="box">
        <h3>Hello, <?php echo htmlspecialchars($user_name); ?></h3>
        <p>Your email: <?php echo htmlspecialchars($user_email); ?></p>
        <h4>Tickets to Purchase:</h4>
        <table>
            <tr><th>Seat Number</th><th>Price (RM)</th></tr>
            <?php foreach ($seats as $seat): ?>
                <tr>
                    <td><?php echo htmlspecialchars($seat['seat_number']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($seat['video_price'], 2)); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr><th>Total</th><th>RM <?php echo number_format($total_price, 2); ?></th></tr>
        </table>
        <form method="POST">
            <button type="submit" name="cancel_purchase" class="btn btn-danger" style="margin-top:20px">Cancel Purchase</button>
        </form>
    </div>

    <!-- Payment Form -->
    <div class="box">
        <h3>Payment Details</h3>
        <form id="payment-form" style="padding:20px">
            <label for="fname">Full Name</label>
            <input type="text" id="fname" style="margin-top:5px; margin-bottom:25px" value="<?php echo htmlspecialchars($user_name); ?>" readonly>

            <label for="email" >Email</label>
            <input type="text" id="email" style="margin-top:5px; margin-bottom:25px" value="<?php echo htmlspecialchars($user_email); ?>" readonly>

            <label for="card-number">Credit Card Number</label>
            <input type="text" id="card-number" style="margin-top:5px; margin-bottom:25px" maxlength="16" placeholder="1111-2222-3333-4444">
            <p class="error" id="card-error">Invalid card number!</p>

            <label for="cvv">CVV</label>
            <input type="text" id="cvv" maxlength="3" style="margin-top:5px; margin-bottom:25px" placeholder="352">
            <p class="error" id="cvv-error">Invalid CVV!</p>

            <button type="submit" id="payNowBtn" class="btn btn-primary" style="margin-top:15px">Pay Now</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $("#payment-form").submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        let cardNumber = $("#card-number").val();
        let cvv = $("#cvv").val();
        let valid = true;

        // Validate card number (16 digits)
        if (!/^\d{16}$/.test(cardNumber)) {
            $("#card-error").show();
            valid = false;
        } else {
            $("#card-error").hide();
        }

        // Validate CVV (3 digits)
        if (!/^\d{3}$/.test(cvv)) {
            $("#cvv-error").show();
            valid = false;
        } else {
            $("#cvv-error").hide();
        }

        // If validation passes, show SweetAlert success message
        if (valid) {
            Swal.fire({
                icon: 'success',
                title: 'Payment Successful!',
                text: 'Your payment has been processed.Please check your mail box.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to process the payment
                    $.ajax({
					url: "process_payment.php",
					type: "POST",
					data: { vid: "<?php echo $video_id; ?>" },
					dataType: "json",
					success: function (response) {
						if (response.status === "success") {
							// Redirect to purchased.php with purchased_id
							window.location.href = "makepdf.php?pdf&id=<?php echo $user_id; ?>&purid=" + response.purchased_id;
						} else {
							// Show error message
							Swal.fire({
								icon: 'error',
								title: 'Payment Failed',
								text: response.message,
								confirmButtonText: 'Try Again'
							});
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.error("AJAX request failed:", textStatus, errorThrown);
						Swal.fire({
							icon: 'error',
							title: 'Payment Error',
							text: 'Something went wrong. Please try again later.',
							confirmButtonText: 'OK'
						});
					}
				});
                }
            });
        }
    });
});
</script>

</body>
</html>
