<?php
include("dataconnection.php");
session_start();

if (isset($_GET["pdf"])) {
    $user_id = $_GET["id"];
    $purchased_id = $_GET["purid"];

    // Fetch payment details
    $resultpay = mysqli_query($connect, "SELECT * FROM purchased WHERE user_id='$user_id' AND purchased_id='$purchased_id'");
    if ($resultpay && mysqli_num_rows($resultpay) > 0) {
        $pay = mysqli_fetch_assoc($resultpay);
    } else {
        die("Payment details not found.");
    }

    // Fetch order details
    $resultorder = mysqli_query($connect, "SELECT * FROM order_item WHERE user_id='$user_id' AND purchased_id='$purchased_id'");
    if ($resultorder && mysqli_num_rows($resultorder) > 0) {
        $order = mysqli_fetch_assoc($resultorder);
    } else {
        die("Order details not found.");
    }

    // Fetch user details
    $resultuser = mysqli_query($connect, "SELECT * FROM user WHERE user_id='$user_id'");
    if ($resultuser && mysqli_num_rows($resultuser) > 0) {
        $usr = mysqli_fetch_assoc($resultuser);
    } else {
        die("User details not found.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" media="all" />
    <style type="text/css">
        body {
			background: url("assets/img/images3.avif");
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			object-fit: cover;
			
            font-family: Arial, sans-serif;
            font-size: 12px;
			padding:20px;
        }
        .receipt-content {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .invoice-wrapper {
            padding: 20px;
            background: #fff;
        }
        .intro {
            margin-bottom: 20px;
        }
        .payment-info, .payment-details, .line-items {
            margin-bottom: 20px;
        }
        .payment-info .row, .payment-details .row, .line-items .headers .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .items .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .total .field {
            margin-bottom: 10px;
        }
        .total .field.grand-total {
            font-size: 16px;
            font-weight: bold;
        }
		h2 { text-align: center; }
    </style>
</head>
<body>
	<h2>Receipt</h2>
    <div class="receipt-content">
        <div class="invoice-wrapper">
            <div class="intro">
                Hi <strong><?php echo $usr["user_name"]; ?></strong>,
                <br>
                This is the receipt for a payment of <strong>RM<?php echo $pay["total_pay"]; ?></strong> (RM) for your works.
            </div>

            <!-- Payment Info -->
            <div class="payment-info">
                <div class="row">
                    <div>
                        <span>Payment ID.</span>
                        <strong><?php echo $pay["purchased_id"]; ?></strong>
                    </div>
                    <div>
                        <span>Payment Date</span>
                        <strong><?php echo $pay["purchased_time"]; ?></strong>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="payment-details">
                <div class="row">
                    <div>
                        <span>Client</span>
                        <strong><?php echo $usr["user_name"]; ?></strong>
                        <p>
                            <a href="#"><?php echo $usr["user_email"]; ?></a>
                        </p>
                    </div>
                    <div>
                        <span>Payment To</span>
                        <strong>Happy Cinema</strong>
                        <p>
                            Prudential Customer Service Centre,<br>
                            Menara KH, <br>
                            Jln Sultan Ismail, <br>
                            50250 Kuala Lumpur, <br>
                            <a href="#">newinti.edu.my</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Line Items -->
            <div class="line-items">
                <div class="headers">
                    <div class="row">
                        <div style="width: 40%;">Seat</div>
                        <div style="width: 30%;">Movie Name</div>
                        <div style="width: 30%; text-align: right;">Amount</div>
                    </div>
                </div>
                <div class="items">
                    <?php
                    $resultorder = mysqli_query($connect, "
                        SELECT * from order_item WHERE user_id='$user_id' AND purchased_id='$purchased_id'
                    ");

                    if ($resultorder && mysqli_num_rows($resultorder) > 0) {
                        while ($order = mysqli_fetch_assoc($resultorder)) {
							
							
							$resultvideo2 = mysqli_query($connect, "SELECT * from video WHERE video_id='" . $order['video_id'] . "'");

							$video = mysqli_fetch_assoc($resultvideo2);
                            ?>
                            <div class="item">
                                <div style="width: 40%;"><?php echo htmlspecialchars($order["seat_number"]); ?></div>
                                <div style="width: 30%;"><?php echo htmlspecialchars($video["video_name"]); ?></div>
                                <div style="width: 30%; text-align: right;"><?php echo "RM " . htmlspecialchars($order["video_price"]); ?></div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='item'><div style='width: 100%;'>No items found.</div></div>";
                    }
                    ?>
                </div>
                <div class="total">
                    <p class="extra-notes">
                        <strong>Extra Notes</strong>
                        Bringing prohibited items into the cinema is not allowed. Violators will be warned and may face legal action.
                    </p>
                    <div class="field">
                        Total Item <span><?php echo mysqli_num_rows($resultorder); ?></span>
                    </div>
                    <div class="field grand-total">
                        Total <span>RM<?php echo $pay["total_pay"]; ?></span>
                    </div>
                </div>
            </div>

            <br><br><br>
            <strong>Copyright © 2025 HAPPY CINEMA</strong>
        </div>
    </div>
	<div style="text-align: center; margin-top: 20px;">
        <a type="button" class="btn btn-light" href="converttopdf.php?pdf&id=<?php echo $user_id; ?>&purid=<?php echo $purchased_id; ?>" name="Download">Download PDF</a>
        <a type="button" class="btn btn-light" style="margin-left: 40px;" href="index.php" name="Download">Main Menu</a>
    </div>
</body>
</html>
	