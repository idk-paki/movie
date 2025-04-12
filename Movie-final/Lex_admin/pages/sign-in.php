<?php
include("dataconnection.php");
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    if (!empty($email) && !empty($password) && !is_numeric($email)) {
        $query = "SELECT * FROM admin WHERE admin_email = '$email' LIMIT 1";
        $result = mysqli_query($connect, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $chk = mysqli_fetch_assoc($result);
            if ($chk['admin_password'] === $password) {
                $_SESSION['admin_id'] = $chk['admin_id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Wrong email or password!";
            }
        } else {
            $error = "The account is not registered yet!";
        }
    } else {
        $error = "Please enter a valid email and password!";
    }

    if (!empty($error)) {
        echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: '$error'});</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Log In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
	<link rel="icon" type="image/png" href="../assets/img/favicon.png">
</head>

<script>
function check() {
    var email = document.getElementById('email');
    var pass = document.getElementById('password');

    var emailRegexp = /^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if (email.value.length === 0 || !emailRegexp.test(email.value)) {
        Swal.fire({icon: 'warning', title: 'Invalid Email', text: 'Please input a correct email!'});
        email.focus();
        return false;
    }

    if (pass.value.length < 5 || pass.value.length > 50) {
        Swal.fire({icon: 'warning', title: 'Invalid Password', text: 'Password must be between 5 and 50 characters!'});
        pass.focus();
        return false;
    }

    return true;
}

function myFunction() {
    var x = document.getElementById("password");
    x.type = x.type === "password" ? "text" : "password";
}
</script>

<body class="bg-gray-200">
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Admin Page (Sign in)</h4>
                                    <div class="row mt-3">
                                        <div class="col-2 text-center ms-auto">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-facebook text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2 text-center px-1">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-github text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2 text-center me-auto">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-google text-white text-lg"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form role="form" class="text-start" method="post" onsubmit="return check();">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required autocomplete="off" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" title="e.g example@gmail.com">
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Password</label>
                                        <input id="password" type="password" class="form-control" name="password" required>
                                    </div>
                                    <div class="form-check form-switch d-flex align-items-center mb-3">
                                        <input class="form-check-input" type="checkbox" id="showpass" onclick="myFunction()">
                                        <label class="form-check-label mb-0 ms-3" for="showpass">Show Password</label>
                                    </div>
                                    <div class="text-center">
                                        <input type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2" name="submit" value="Sign in!">
                                    </div>
                                    
                                    <?php if (isset($_POST["submit"]) && $error != ""): ?>
										<p class="text-danger text-center"><?php echo $error; ?></p>
										<?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer position-absolute bottom-2 py-2 w-100">
                <div class="container">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-12 col-md-6 my-auto"></div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
