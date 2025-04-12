<?php
include("dataconnection.php");
session_start();

if (isset($_GET["vid"]) && isset($_SESSION['user_id'])) {
    $video_id = $_GET["vid"];
    $user_id = $_SESSION['user_id'];

    // check cart，if got will bring to payment page
    $cart_check_query = "SELECT * FROM cart WHERE video_id='$video_id' AND user_id='$user_id'";
    $cart_result = mysqli_query($connect, $cart_check_query);

    if (mysqli_num_rows($cart_result) > 0) {
        // bring to payment page purchased.php
        header("Location: purchased.php?vid=$video_id");
        exit();
    }
}

if (isset($_GET["vid"])) {  // Removed extra closing parenthesis
    $video_id = $_GET["vid"];
	$user_id = $_SESSION['user_id'];

    // Validate that video_id is numeric to avoid SQL injection or errors
    if (!is_numeric($video_id)) {
        echo "<script>
                alert('Invalid video ID!');
                window.location.href='index.php';
              </script>";
        exit();
    }

    $result = mysqli_query($connect, "SELECT * FROM video WHERE video_id='$video_id'");

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>
                alert('Invalid video ID!');
                window.location.href='index.php';
              </script>";
        exit();
    }

    $row2 = mysqli_fetch_assoc($result);

}

if (!isset($_SESSION['user_id'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Ticket</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert -->
</head>
<body>
    <script>
        // SweetAlert popup
        Swal.fire({
            icon: 'warning',
            title: 'Access Denied',
            text: 'Please log in!',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                
                window.location.href = 'index.php';
            }
        });
    </script>
</body>
</html>
<?php
   
} else {
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($connect, "SELECT * FROM user WHERE user_id='$user_id'");
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE HTML>
<html lang="zxx">
	
<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Buy Ticket</title>
		<!-- Favicon Icon -->
		<link rel="icon" type="image/png" href="assets/img/favcion.png" />
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" media="all" />
		<!-- Slick nav CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/slicknav.min.css" media="all" />
		<!-- Iconfont CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/icofont.css" media="all" />
		<!-- Owl carousel CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/owl.carousel.css">
		<!-- Popup CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/magnific-popup.css">
		<!-- Main style CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/style.css" media="all" />
		<!-- Responsive CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/responsive.css" media="all" />
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	 <style>
		.disabled {
			background-color: grey !important;
			pointer-events: none;
			color: white;
			opacity: 0.6;
		}
        .ticket-box-table {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        table {
            border-collapse: collapse;
        }
        td {
            width: 40px;
            height: 40px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid black;
            cursor: pointer;
        }
        .selected {
            background-color: blue;
            color: white;
        }
        .disabled {
            background-color: gray;
            pointer-events: none;
        }
    </style>
	<body>
		<!-- Page loader -->
	    <div id="preloader"></div>
		<!-- header section start -->
		<header class="header">
			<div class="container">
				<div class="header-area">
					<div class="logo">
						<a href="index.php"><img src="assets/img/logo.png" alt="logo" /></a>
					</div>
					<div class="header-right">
						
						
					</div>
					<div class="menu-area">
						<div class="responsive-menu"></div>
					    <div class="mainmenu">
                            <ul id="primary-menu">
                                <div><li>
									<?php
									if(!isset($_SESSION['user_id']))
									{
										?>
									
									<a style="font-size: 18px;" href="#">Welcome Guest!</a>
									<?php
									}else
									{
										
										$find_user_id = mysqli_query($connect, "SELECT * FROM user WHERE user_id='" . $_SESSION['user_id'] . "'");

										$find_user_id2 = mysqli_fetch_assoc($find_user_id);
										
										echo '<span style="color:white; margin-right: 40px; font-size: 16px;">Username : '.$find_user_id2["user_name"].'</span>';
									}
									?>
									</li>
									<li>
									<?php
									if(!isset($_SESSION['user_id']))
									{
									?>
									
										<a class="login-popup banner" href="#">Login</a>
										
									
									<?php
									}else
									{
										echo"<a class='banner' style='color:white' href='logout.php'>Logout</a>";
									}
									?>
								</li></div>
                                
                            </ul>
					    </div>
					</div>
				</div>
			</div>
		</header>
		
		<!-- header section end -->
		<br><br><br><br><br><br><br><br>
		<!-- transformers area start -->
		<section  class="transformers-area">
			<div class="container">
				<div class="transformers-box" style="height:450px">
					<div class="row flexbox-center">
						<div class="col-lg-5 text-lg-left text-center">
							<div class="transformers-content">
								<img src="../Lex_admin/pages/images/<?php echo $row2["video_img"];?>"" alt="about" />
							</div>
						</div>
						<div class="col-lg-7">
							<div class="transformers-content">
								<h2><?php echo $row2["video_name"]; ?></h2>
								
								<ul>
									<li>
										<div class="transformers-left">
											Date Time:
										</div>
										<div class="transformers-right">
											<p ><?php echo $row2["video_date"]; ?></p>
										</div>
									</li>
									<li>
										<div class="transformers-left">
											Price:
										</div>
										<div class="transformers-right">
											RM <?php echo $row2["video_price"]; ?>
										</div>
									</li>
									<li>
										<div class="transformers-left">
											Location:
										</div>
										<div class="transformers-right">
											HB Cinema Box Corner
										</div>
									</li>
									<li>
										<div class="transformers-left">
											Overview:
										</div>
										<div class="transformers-right">
											
									<p><?php echo  $row2["video_details"]; ?></p>
										</div>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</section><!-- transformers area end -->
		<!-- details area start -->
		<section class="details-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="details-content" style="width:1250px">
                    <div class="details-overview">
                        <h2>Overview</h2>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="buy-ticket-box">
                                    <h4>Buy Tickets</h4>
                                    <h6 style="color:red;">Screen</h6>
                                    <div class="ticket-box-table">
                                        <table id="left-seats"></table>
                                        <table id="center-seats"></table>
                                        <table id="right-seats"></table>
                                    </div>

                                    <h5 style="color:white">Selected Seat: <span id="selected-count" style="color:white">0</span></h5>
                                    <h5 style="color:white" >Total: RM <span id="total-price" style="color:white">0</span></h5>
                                    <button class="theme-btn" id="buy-btn" style="display: none;"><i class="icofont icofont-ticket"></i> BUY TICKET</button>
                                </div>
                            </div>
                            <div class="col-lg-3 offset-lg-1">
                                <div class="buy-ticket-box mtr-30">
                                    <h4>Your Have Buy</h4>
                                    <ul>
                                        <li><p>Location</p><span>HB Cinema Box Corner</span></li>
                                        <li><p>TIME</p><span><?php echo $row2["video_date"]; ?></span></li>
                                        <li><p>Movie name</p><span><?php echo $row2["video_name"]; ?></span></li>
                                        <li><p>Ticket number</p><span>0 seats</span></li>
                                        <li><p>Price</p><span>RM 0</span></li>
                                    </ul>
                                </div>
                            </div>
                          

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
const rows = 5;
const cols = 7;
const seatPrice = <?php echo $row2["video_price"]; ?>;
let selectedSeats = [];
let bookedSeats = [];

function createSeats(section, prefix, startNumber) {
    const table = document.getElementById(section);
    table.innerHTML = "";

    for (let i = 1; i <= rows; i++) {
        let row = document.createElement("tr");
        for (let j = 0; j < cols; j++) {
            let seatNumber = `${prefix}${i}${startNumber + j}`;
            let seat = document.createElement("td");
            seat.textContent = seatNumber;
            seat.dataset.seatNumber = seatNumber;
            
            if (bookedSeats.includes(seatNumber)) {
                seat.classList.add("disabled");
            } else {
                seat.onclick = toggleSeat;
            }

            row.appendChild(seat);
        }
        table.appendChild(row);
    }
}

function toggleSeat() {
    if (this.classList.contains("selected")) {
        this.classList.remove("selected");
        selectedSeats = selectedSeats.filter(seat => seat !== this.dataset.seatNumber);
    } else {
        this.classList.add("selected");
        selectedSeats.push(this.dataset.seatNumber);
    }
    updateSelection();
}

function updateSelection() {
    let seatCount = selectedSeats.length;
    let totalPrice = seatCount * seatPrice;

    document.getElementById("selected-count").textContent = seatCount;
    document.getElementById("total-price").textContent = totalPrice;
    document.getElementById("buy-btn").style.display = seatCount > 0 ? "block" : "none";
}

$("#buy-btn").click(function () {
    if (selectedSeats.length === 0) {
        alert("Please select a seat!");
        return;
    }

    $.post("buy_ticket.php", {
        seat_number: selectedSeats.join(","),
        video_id: <?php echo $video_id; ?>,
        user_id: <?php echo $_SESSION["user_id"]; ?>
    }, function(response) {
        if (response === "Success") {
            $(".selected").addClass("disabled").removeClass("selected");
            selectedSeats = [];
            updateSelection();
            window.location.href = "purchased.php?vid=<?php echo $video_id; ?>";
        } else {
            alert(response);
        }
    }).fail(function () {
        alert("Purchase error, please try again!");
    });
});


function fetchBookedSeats() {
    $.post("fetch_booked_seats.php", { video_id: <?php echo $video_id; ?> }, function(response) {
        bookedSeats = response;
        createSeats("left-seats", "A", 0);
        createSeats("center-seats", "B", 0);
        createSeats("right-seats", "C", 0);
    }).fail(function () {
        alert("Failed to load booked seats.");
    });
}

fetchBookedSeats();
</script>
<!-- details area end -->
		<!-- footer section start -->
		<footer class="footer">
			<div class="container">
				<div class="row">
                    <div class="col-lg-3 col-sm-6">
						<div class="widget">
							<img src="assets/img/logo.png" alt="about" />
							<p>7th Harley Place, London W1G 8LZ United Kingdom</p>
							<h6><span>Call us: </span>(+880) 111 222 3456</h6>
						</div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
						<div class="widget">
							<h4>Legal</h4>
							<ul>
								<li><a href="#">Terms of Use</a></li>
								<li><a href="#">Privacy Policy</a></li>
								<li><a href="#">Security</a></li>
							</ul>
						</div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
						<div class="widget">
							<h4>Account</h4>
							<ul>
								<li><a href="#">My Account</a></li>
								<li><a href="#">Watchlist</a></li>
								<li><a href="#">Collections</a></li>
								<li><a href="#">User Guide</a></li>
							</ul>
						</div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
						<div class="widget">
							<h4>Newsletter</h4>
							<p>Subscribe to our newsletter system now to get latest news from us.</p>
							<form action="#">
								<input type="text" placeholder="Enter your email.."/>
								<button>SUBSCRIBE NOW</button>
							</form>
						</div>
                    </div>
				</div>
				<hr />
			</div>
			<div class="copyright">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 text-center text-lg-left">
							<div class="copyright-content">
								
							</div>
						</div>
						<div class="col-lg-6 text-center text-lg-right">
							<div class="copyright-content">
								<a href="#" class="scrollToTop">
									Back to top<i class="icofont icofont-arrow-up"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer><!-- footer section end -->
		<!-- jquery main JS -->
		<script src="assets/js/jquery.min.js"></script>
		<!-- Bootstrap JS -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- Slick nav JS -->
		<script src="assets/js/jquery.slicknav.min.js"></script>
		<!-- owl carousel JS -->
		<script src="assets/js/owl.carousel.min.js"></script>
		<!-- Popup JS -->
		<script src="assets/js/jquery.magnific-popup.min.js"></script>
		<!-- Isotope JS -->
		<script src="assets/js/isotope.pkgd.min.js"></script>
		<!-- main JS -->
		<script src="assets/js/main.js"></script>
	</body>

</html>