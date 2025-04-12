<?php include("dataconnection.php"); 

session_start();





?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Happy Cinema</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">




		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Moviepoint - Online Movie,Vedio and TV Show HTML Template</title>
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
		
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>

<body>	
	
	<?php
	
	if (isset($_GET["vid"])) {  // Removed extra closing parenthesis
    $video_id = $_GET["vid"];

    // Validate that video_id is numeric to avoid SQL injection or errors
    if (!is_numeric($video_id)) {
        echo "<script>
                alert('Invalid video ID!');
                window.location.href='video_list.php';
              </script>";
        exit();
    }

    $result = mysqli_query($connect, "SELECT * FROM video WHERE video_id='$video_id'");

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>
                alert('Invalid video ID!');
                window.location.href='video_list.php';
              </script>";
        exit();
    }
		
		if (!isset($_SESSION['user_id'])) {
		echo '<script>
				document.addEventListener("DOMContentLoaded", function() {
					let loginArea = document.querySelector(".login-area");
					if (loginArea) {
						loginArea.style.display = "flex";
					}
				});
			  </script>';
	}
	else
	{
		echo "<script>
        window.location.href='movie-details.php?vid=$video_id';
      </script>";

	}

	}


	if(isset($_GET["submitbtn"])) {
		$user_name = $_GET["cust_name"];
		$user_password = $_GET["cust_password"];
		$user_email = $_GET["cust_email"];
		$confirm_password = $_GET["confirm_password"];
		
		$errors = array();

		$checkUser = "SELECT * FROM user WHERE user_email = '$user_email'";
		$result = mysqli_query($connect, $checkUser);
		$count = mysqli_num_rows($result);

		$checkname = "SELECT * FROM user WHERE user_name = '$user_name'";
		$result2 = mysqli_query($connect, $checkname);
		$count2 = mysqli_num_rows($result2);

		$errorfind = 0;
		if($count > 0 || $count2 > 0) {
			$errorfind = 1;
		}

		$count3 = 0;
		if($user_password != $confirm_password) {
			echo "<script>Swal.fire({icon: 'error', title: 'Password Mismatch', text: 'Your Confirm Password does not match your Password!'});</script>";
			$count3 = 1;
		}

		if($errorfind > 0) {
			if($count > 0) {
				$errors['e'] = "Email already in use!";
				echo "<script>Swal.fire({icon: 'warning', title: 'Email Exists', text: 'User email already signed up. Please use another email!'});</script>";
			}

			if($count2 > 0) {
				$errors['n'] = "Username already in use!";
				echo "<script>Swal.fire({icon: 'warning', title: 'Username Exists', text: 'User name already signed up. Please use another name!'});</script>";
			}
		}

		if($count2 == 0 && $count == 0 && $count3 == 0) {
			$sql = "INSERT INTO user(user_name, user_password, user_email) VALUES('$user_name', '$user_password', '$user_email')";
				
			if(mysqli_query($connect, $sql)) {
				 $last_insert_id = mysqli_insert_id($connect);

				// Set session
				$_SESSION["user_id"] = $last_insert_id;
				echo "<script>Swal.fire({icon: 'success', title: 'Registration Successful', text: 'User successfully added! Please check your email.'}).then(() => {window.location.href = 'index.php';});</script>";
				
			} else {
				echo "<script>Swal.fire({icon: 'error', title: 'Database Error', text: 'An error occurred while adding the user. Please try again.'});</script>";
			}
		}
	}

	if(isset($_GET["loginbtn"])) {
		if(empty($_GET["c_email"]) || empty($_GET["c_password"])) {
			echo "<script>Swal.fire({icon: 'warning', title: 'Input Error', text: 'Email or password is empty.'});</script>";
		} else {
			$user_email = mysqli_real_escape_string($connect, $_GET["c_email"]);
			$user_password = mysqli_real_escape_string($connect, $_GET["c_password"]);

			$resultlo = mysqli_query($connect, "SELECT * FROM user WHERE user_email='$user_email' ");
			$resultban = mysqli_query($connect, "SELECT * FROM user WHERE user_email='$user_email' ");
			$result = mysqli_query($connect, "SELECT * FROM user WHERE user_email='$user_email' AND user_password='$user_password' ");

			$count = mysqli_num_rows($result);


			
				if($count == 1) {
					$row = mysqli_fetch_assoc($result);
					$_SESSION["user_id"] = $row["user_id"];
					echo "<script>Swal.fire({icon: 'success', title: 'Login Successful', text: 'Welcome back, {$row["user_name"]}!'}).then(() => {window.location.href = 'index.php';});</script>";
				} else {
					echo "<script>Swal.fire({icon: 'error', title: 'Login Failed', text: 'Wrong email or password!'});</script>";
				}
			
		}
	}
	?>

</body>	


<style>

	.login-area {
		display: none;
		position: fixed;
		top: 50%;
		left: 20%;
		transform: translate(-50%, -50%);
		background: white; 
		z-index: 1000;
		box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
		width: 400px;
		max-height: 80vh; 
		overflow-y: auto; 
		border-radius: 10px;
	}

	.login-box {
		width: 100%;
		padding: 10px ;
	}

	
    input[type="text"], input[type="password"], input[type="email"] {
        color: #333; 
        background-color: #f9f9f9; 
        border: 1px solid #ccc; 
        padding: 10px;
        font-size: 14px;
    }

    input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
        border-color: #007bff; 
        outline: none;
    }

    .theme-btn {
        background-color: #007bff; 
        color: #fff; 
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        text-align: center;
        display: block;
        width: 100%;
        text-decoration: none;
        margin-top: 10px;
    }

    .theme-btn:hover {
        background-color: #0056b3; 
    }


	.banner {
		display: inline-block;
		position: relative;
		margin-left: 15px;
		padding-bottom: 20px;
		cursor: pointer;
		font-size: 18px;
	}

	.banner::before {
		background: transparent;
		bottom: -2px;
		left: 0;
		right: 0;
		transition: 0.4s;
		content: "";
		height: 4px;
		position: absolute;
		z-index: 99;
	}

	.banner.active {
		color: #fff;
	}

	.banner.active::before,
	.banner:hover::before {
		background: #eb315a;
	}

</style>


	<body>
		<!-- Page loader -->
	    <div id="preloader"></div>
		
		<!-- header section start -->
		<header class="header">
			<div class="container">
				<div class="header-area">
					<div class="logo" style="margin-top:-60px">
						<a href="index.php"><img src="assets/img/logo.png" alt="logo" /></a>
					</div>
					<div class="header-right">
						
						<form name="search_form" method="GET" action="" style="margin-top:52px; margin-right:55px">
							<select>
								<option value="search">Search</option>
								
							</select>
							<input class="border" style="margin-left:10px; background-color:rgba(200,200,200,0.2)" type="text" name="searchname" placeholder="Video Name">
							<button class="button" type="submit" value="Search" name="searchbtn" ><i class="icofont icofont-search"></i></button>
						</form>
						
						
					<div class="menu-area" style="margin-top: 50px">
						<div class="responsive-menu"></div>
					    <div class="mainmenu">
                            <ul id="primary-menu" style="display:flex; justify-content: space-between;">
                                <div>

									<li><a class="banner" href="#" onclick="scrollDown()">Spotlight</a></li>
								</div>
									<script>
										function scrollDown() {
											document.querySelector(".row.flexbox-center").scrollIntoView({
												behavior: "smooth"
											});
										}
									</script>
									

									
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
		
		
		
		<style>

		.true {
		  color: green;
		}

		.false {
		  color: red;
		}

		a:link { text-decoration: none; }
		
		
		.bg{
			
			background: url("assets/img/images.png");
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			object-fit: cover;
		}
		
		</style>	

	
	
		<script>
			var rer = function() {
				
				var password = document.getElementById("password")
			  , confirm_password = document.getElementById("confirm_password");
				
				if(confirm_password.value== "")
				{
					confirm_password.setCustomValidity("Confirm Passwords is empty");
				}
				else{

					if(password.value != confirm_password.value)
					{
						confirm_password.setCustomValidity("Passwords Don't Match");
					}else {
					confirm_password.setCustomValidity('');
					}
				}
				
				
				if (document.getElementById('password').value ==
					document.getElementById('confirm_password').value) {
						document.getElementById('message').style.color = 'green';
						document.getElementById('message').innerHTML = 'Matching';
				} else {
					document.getElementById('message').style.color = 'red';
					document.getElementById('message').innerHTML = 'Not Matching';
				 } 
			  
			}
			
			function myFunction() {
			  var x = document.getElementById("password");
			  if (x.type === "password") {
				x.type = "text";
			  } else {
				x.type = "password";
			  }
			}
			function myFunction2() {
			  var x = document.getElementById("confirm_password");
			  if (x.type === "password") {
				x.type = "text";
			  } else {
				x.type = "password";
			  }
			}
			function myFunction3() {
			  var x = document.getElementById("loginpass");
			  if (x.type === "password") {
				x.type = "text";
			  } else {
				x.type = "password";
			  }
			}

				
			function check()
			{
				
			var myInput = document.getElementById("password");
			var lt = document.getElementById("lt");
			var cp = document.getElementById("cp");
			var num = document.getElementById("num");
			var length = document.getElementById("length");

			// When the user clicks on the password field, show the msg box
			myInput.onfocus = function() {
			  document.getElementById("msg").style.display = "block";
			}



			// When the user starts to type something inside the password field
			myInput.onkeyup = function() {
			  // Validate lowercase letters
			  var lowerCaseLetters = /[a-z]/g;
			  if(myInput.value.match(lowerCaseLetters)) {
				lt.classList.remove("false");
				lt.classList.add("true");
			  } else {
				lt.classList.remove("true");
				lt.classList.add("false");
			}

			  // Validate capital letters
			  var upperCaseLetters = /[A-Z]/g;
			  if(myInput.value.match(upperCaseLetters)) {
				cp.classList.remove("false");
				cp.classList.add("true");
			  } else {
				cp.classList.remove("true");
				cp.classList.add("false");
			  }

			  // Validate numbers
			  var numbers = /[0-9]/g;
			  if(myInput.value.match(numbers)) {
				num.classList.remove("false");
				num.classList.add("true");
			  } else {
				num.classList.remove("true");
				num.classList.add("false");
			  }

			  // Validate length
			  if(myInput.value.length >= 8) {
				length.classList.remove("false");
				length.classList.add("true");
			  } else {
				length.classList.remove("true");
				length.classList.add("false");
			  }
			}
			}

		</script>


		<div class="login-area">
			<div class="login-box bg" style="margin-top:5%">
				<a href="#"><i class="icofont icofont-close"></i></a>
				
				<form id="login-form">
					<h2 style="margin-top:20px">LOGIN</h2>
					<h6 style="margin-top:60px">EMAIL ADDRESS</h6>
					<input class="inputform" type="email" name="c_email" placeholder="Email"  pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" title="e.g exmaple@gmail.com" required autocomplete="off">
					<h6>PASSWORD</h6>
					<input class="inputform" id="loginpass" type="password" name="c_password" placeholder="Password" required autocomplete="off">
						<input class="inputform" style="margin-left:10%;"type="checkbox" onclick="myFunction3()"><p style="margin-left:20%;margin-top:-11%">Show Password</p>
					
					<br>
					<br>

					<button  id="button" class="theme-btn" style="margin: auto; display: block; width:200px; padding:8px;" type="submit"  sty=value="Send!" name="loginbtn" onclick="check()">Login</button>
					<div class="login-signup" style="margin-top:20px">
						
						<button class="theme-btn" style="margin-top:20px;margin-left:-7%" id="toggle-register">Don't have an account? Register</button>
					</div>
				</form>


				<!-- Register form (initially hidden) -->
				<form action="#" id="register-form" class="bg" style="display: none; margin-bottom:10px;" >
					<h2>Register</h2>
					
					<p>Username<b style="color:red;">*</b></p>
					<input class="inputform" style="margin-top:-1%;"id="cus_n" type="text" name="cust_name" placeholder="User name" onclick="check()" required autocomplete="off">
					<p style="color:red;margin-left:30%;margin-top:-1%;"><?php if(isset($errors['n'])) echo $errors['n']; ?></p>
										
									
					<p >User Email<b style="color:red;">*</b></p>
					<input class="inputform" type="email"style="margin-top:-1%;" name="cust_email" placeholder="Email" onclick="check()" required autocomplete="off" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" title="e.g exmaple@gmail.com">
					<p style="color:red;margin-left:30%;margin-top:-1%;"><?php if(isset($errors['e'])) echo $errors['e']; ?></p>
										
					<p >User Password<b style="color:red;">*</b></p>
					<input class="inputform" onkeyup='rer();check();'  id="password" required  type="password" name="cust_password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  placeholder="Password" required autocomplete="off" />
					<input class="inputform" type="checkbox" onclick="myFunction()"><p style="margin-top: -38px;margin-left: 20px;">Show Password</p>
										
										
					<div id="msg">
						<p id="lt" class="false">&#10004; A <b>lowercase</b> letter</p>
						<p id="cp" class="false">&#10004;A <b>capital (uppercase)</b> letter</p>
						<p id="num" class="false">&#10004;A <b>number</b></p>
						<p id="length" class="false">&#10004;Minimum <b>8 characters</b></p>
					</div>

					<p >User Confirm Password<b style="color:red;">*</b> <i class="bi bi-eye-slash" id="toggleconfirm_password" ></i></p>
					<input  onkeyup='rer();' class="inputform" id="confirm_password" required  type="password" name="confirm_password" placeholder="Confirm Password" autocomplete="off"required />
					<input class="inputform" type="checkbox" onclick="myFunction2()"><p style="margin-top: -38px;margin-left: 20px;">Show Confirm Password</p>
					<span  id='message'></span>

					<div class="register-agree">
						<input type="checkbox" required />
						<a href="https://mdec.my/terms-conditions">I agree to the terms and conditions</a>
					</div>
					
					<input id="button" style="padding:10px;" type="submit" class="btn btn-secondary" value="Sign up" name="submitbtn" ></input>
					
					<div class="login-signup" >
						<span >Already have an account? <button class="theme-btn" id="toggle-login">Log In</button></span>
					</div>
				
				</form>
			

				<script>
					// Toggle between login and register forms
					document.getElementById('toggle-register').addEventListener('click', function() {
						document.getElementById('login-form').style.display = 'none';
						document.getElementById('register-form').style.display = 'block';
					});

					document.getElementById('toggle-login').addEventListener('click', function() {
						document.getElementById('register-form').style.display = 'none';
						document.getElementById('login-form').style.display = 'block';
					});
				</script>

								
			</div>
		</div>
		
		
	
		
		
		<!-- hero area start -->
		<section class="hero-area" id="home">
			<div class="container">
				<div class="hero-area-slider">
				<?php
						$nosearch=mysqli_query($connect,"SELECT * from video Order BY video_date DESC");
						while($row=mysqli_fetch_assoc($nosearch))
						{
						?>
					<div class="row hero-area-slide">
						<div class="col-lg-6 col-md-5">
							<div class="hero-area-content">
								<div class="video-container">
								  <img src="../Lex_admin/pages/images/<?php echo $row["video_img"];?>" alt="Thumbnail" class="thumbnail">
								  <video class="video" controls muted loop> 
									<source src="../Lex_admin/pages/video/<?php echo $row["video_file"];?>" type="video/mp4">
									
								  </video>
								</div>


								<style>
								.video-container {
								  position: relative;
								  width: 300px; /* Adjust as needed */
								  height: 400px; /* Adjust as needed */
								  overflow: hidden;
								}

								.thumbnail {
								  width: 100%;
								  height: 100%;
								  object-fit: cover;
								  transition: opacity 0.3s ease;
								}

								.video {
								  position: absolute;
								  top: 0;
								  left: 0;
								  width: 100%;
								  height: 100%;
								  object-fit: cover;
								  opacity: 0;
								  transition: opacity 0.3s ease;
								}

								.video-container:hover .thumbnail {
								  opacity: 0;
								}

								.video-container:hover .video {
								  opacity: 1;
								}
								</style>

								<script>
								  document.addEventListener("DOMContentLoaded", function () {
									const videoContainer = document.querySelector('.video-container');
									const video = document.querySelector('.video');

									// Ensure the video is loaded
									video.load();

									videoContainer.addEventListener('mousedown', () => {
									  video.play();
									});

									videoContainer.addEventListener('mouseup', () => {
									  video.pause();
									  video.currentTime = 0; // Reset video to the beginning
									});

									videoContainer.addEventListener('mouseleave', () => {
									  video.pause();
									  video.currentTime = 0;
									});
								  });
								</script>

							</div>
						</div>
						
						<div class="col-lg-6 col-md-7">
							<div class="hero-area-content pr-50">
								<h2><?php echo $row["video_name"];?></h2>
								<div class="review">
									<div class="author-review">
										<i class="icofont icofont-star"></i>
										<i class="icofont icofont-star"></i>
										<i class="icofont icofont-star"></i>
										<i class="icofont icofont-star"></i>
										<i class="icofont icofont-star"></i>
									</div>
									<h4>180k voters</h4>
								</div>
								<p><?php echo $row["video_details"];?></p>
								<h3>On Date:</h3>
								<div class="slide-cast">
									<p><?php echo $row["video_date"];?></p>
									<h3>RM<?php echo $row["video_price"];?></h3>
								</div>
								<div class="slide-trailor">
										<a class="theme-btn theme-btn2" href="index.php?vid=<?php echo $row["video_id"]; ?>"><i class="icofont icofont-play"></i>Buy Tickets Now!</a>
									</div>
							</div>
						</div>
					</div>
				<?php
						}
					
						?>
				</div>
				<div class="hero-area-thumb">
					<div class="thumb-prev">
						<div class="row hero-area-slide">
							<div class="col-lg-6">
								<div class="hero-area-content">
									<img src="assets/img/slide3.png" alt="about" />
								</div>
							</div>
							<div class="col-lg-6">
								<div class="hero-area-content pr-50">
									<h2>Last Avatar</h2>
									<div class="review">
										<div class="author-review">
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
										</div>
										<h4>180k voters</h4>
									</div>
									<p>She is a devil princess from the demon world. She grew up sheltered by her parents and doesn't really know how to be evil or any of the common actions,   She is unable to cry due to Keita's accidental first wish, despite needed for him to wish...</p>
									<h3>Cast:</h3>
									<div class="slide-cast">
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast1.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast2.html" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast3.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast4.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast5.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast6.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast7.png" alt="about" />
										</div>
										<div class="single-slide-cast text-center">
											5+
										</div>
									</div>
									<div class="slide-trailor">
										<a class="theme-btn theme-btn2" ><i class="icofont icofont-play"></i>Buy Tickets Now!</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="thumb-next">
						<div class="row hero-area-slide">
							<div class="col-lg-6">
								<div class="hero-area-content">
									<img src="assets/img/slide1.png" alt="about" />
								</div>
							</div>
							<div class="col-lg-6">
								<div class="hero-area-content pr-50">
									<h2>The Deer God</h2>
									<div class="review">
										<div class="author-review">
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
										</div>
										<h4>180k voters</h4>
									</div>
									<p> </p>
									<h3>Cast:</h3>
									<div class="slide-cast">
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast1.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast2.html" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast3.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast4.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast5.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast6.png" alt="about" />
										</div>
										<div class="single-slide-cast">
											<img src="assets/img/cast/cast7.png" alt="about" />
										</div>
										<div class="single-slide-cast text-center">
											5+
										</div>
									</div>
									<div class="slide-trailor">
										<h3>Watch Trailer</h3>
										<a class="theme-btn theme-btn2" href="#"><i class="icofont icofont-play"></i> Tickets</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- hero area end -->
		
		
		
	<!-- portfolio section start -->
	<section class="portfolio-area pt-60">
		<div class="container">
			<div class="row flexbox-center">
				<div class="col-lg-12 text-center"> 
					<div class="section-title">
						<h1><i class="icofont icofont-movie"></i> Spotlight This Month</h1>
					</div>
				</div>
			</div>
			<hr />
			<?php
			if(isset($_GET["searchbtn"])) {
				$result=$_GET["searchname"];
				$search=mysqli_query($connect,"SELECT * from video WHERE video_name like '%$result%' Order BY video_date DESC ");
				if(mysqli_num_rows($search) == 0) {
					?>
					<div class="row">
						<div class="col-12 text-center">
							<br><br><br><br><br><br>
							<h1>Result could not be found!</h1>
							<br><br><br><br><br><br>
						</div>
					</div>
					<?php
				} else {
					$count = 0; 
					while($row=mysqli_fetch_assoc($search)) {
						if ($count % 4 == 0) { 
							if ($count > 0) echo '</div>'; 
							echo '<div class="row portfolio-item">'; 
						}
						?>
						<div class="col-lg-3 col-md-4 col-sm-6"> 
							<div class="single-portfolio">
							
								<div class="single-portfolio-img">
								<a  href="index.php?vid=<?php echo $row["video_id"]; ?>">
									<div style="width:200px;height:400px;">
										<img src="../Lex_admin/pages/images/<?php echo $row["video_img"];?>" alt="portfolio" />
										<video class="video" controls muted loop> 
											<source src="../Lex_admin/pages/video/<?php echo $row["video_file"];?>" type="video/mp4">
											<i class="icofont icofont-ui-play"></i>
										</video>
									</div>
									</a>
								</div>
								
								<div class="portfolio-content" style="margin-top:-30%">
									<h2><?php echo $row["video_name"];?></h2>
									<div class="review">
										<div class="author-review">
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
										</div>
										<h4>180k voters</h4>
									</div>
								</div>
							</div>
						</div>
						<?php
						$count++; 
					}
					echo '</div>';
				}
			}else
			{
				$count = 0; 
				$nosearch=mysqli_query($connect,"SELECT * from video Order BY video_date DESC ");
				while($row=mysqli_fetch_assoc($nosearch))
				{
						if ($count % 4 == 0) { 
							if ($count > 0) echo '</div>'; 
							echo '<div class="row portfolio-item">'; 
						}
						?>
						<div class="col-lg-3 col-md-4 col-sm-6"> 
							<div class="single-portfolio">
							
								<div class="single-portfolio-img">
								<a  href="index.php?vid=<?php echo $row["video_id"]; ?>">
									<div style="width:200px;height:400px;">
										<img src="../Lex_admin/pages/images/<?php echo $row["video_img"];?>" alt="portfolio" />
										<video class="video" controls muted loop> 
											<source src="../Lex_admin/pages/video/<?php echo $row["video_file"];?>" type="video/mp4">
											<i class="icofont icofont-ui-play"></i>
										</video>
									</div>
									</a>
								</div>
								
								<div class="portfolio-content" style="margin-top:-30%">
									<h2><?php echo $row["video_name"];?></h2>
									<div class="review">
										<div class="author-review">
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
											<i class="icofont icofont-star"></i>
										</div>
										<h4>180k voters</h4>
									</div>
								</div>
							</div>
						</div>
						<?php
						$count++; 
					}
					echo '</div>';
				}
			?>
		</div>
	</section>
	<!-- portfolio section end -->


		
		
		
		<!-- footer section start -->
		<footer class="footer">
			<div class="container">
				<div class="row">
                    <div class="col-lg-3 col-sm-6">
						<div class="widget">
							<img src="assets/img/logo.png" alt="about" />
							<p>Persiaran Perdana BBN Putra Nilai, 71800 Nilai, Negeri Sembilan</p>
							<h6><span>Call us: </span>(+60) 11 3132 4345</h6>
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
		</footer>
		<!-- footer section end -->


		
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
