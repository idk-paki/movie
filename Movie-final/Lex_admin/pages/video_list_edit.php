<?php
include("dataconnection.php");
session_start();

if (isset($_GET["edit"])) {  // Removed extra closing parenthesis
    $video_id = $_GET["edit"];

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

    $row2 = mysqli_fetch_assoc($result);



if (!isset($_SESSION['admin_id'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LEX MOVIE ADMIN</title>
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
                
                window.location.href = 'sign-in.php';
            }
        });
    </script>
</body>
</html>
<?php
   
} else {
    $admin_id = $_SESSION['admin_id'];
    $result = mysqli_query($connect, "SELECT * FROM admin WHERE admin_id='$admin_id'");
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   LEX MOVIE ADMIN
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<script>
function confirmation(videoId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You are about to edit this video.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, edit it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the edit page if confirmed
            window.location.href = `video_list_edit.php?yesedit${videoId}`;
        }
    });
}
</script>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">LEX MOVIE ADMIN</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
		 <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Video pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link active bg-gradient-dark text-white" href="../pages/video_list.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Video List</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/add_video.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Add Video</span>
          </a>
        </li>
		<li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">order pages</h6>
        </li>
		<li class="nav-item">
          <a class="nav-link text-dark" href="../pages/sit_manage.php">
            <i class="material-symbols-rounded opacity-5">receipt_long</i>
            <span class="nav-link-text ms-1">Order Search</span>
          </a>
        </li>
		
        <?php 
			if($admin_id=='1')
			{
		?>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
       
        <li class="nav-item">
          <a class="nav-link text-dark" href="../pages/sign-up.php">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Create New Account</span>
          </a>
        </li>
		<?php 
			}
		?>
      </ul>
    </div>
    
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Add Video</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
		  <?php echo"<br>Welcome " .$row["admin_name"]; ?>
            <div class="input-group input-group-outline">
              <label class="form-label"> </label>
             
            </div>
			<button onclick="window.location.href='logout.php'" style="background-color: #dc3545; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">Logout</button>

          </div>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">
            
            
          
		</div>
		
	
              </ul>
            </li>
            
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Add Video</h6>
				<h6 class="text-white text-capitalize ps-3"><a class="text-white text-capitalize ps-3" href="video_list.php">Back</a></h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
			  
                 <form name="editfrm" method="post" enctype="multipart/form-data">
					<label style="margin-left:5%;font-size:20px;">Video Name:</label>
					<input  style="border-radius:25px;" type="text" name="video_name"  value="<?php echo  $row2['video_name']; ?>" required><br>
					<label style="margin-left:5%;font-size:20px;">Video Price:</label>
					<input  style="border-radius:25px;"type="text" name="video_price" value="<?php echo $row2['video_price'];?>" required><br>
					<label style="margin-left:5%;font-size:20px;">Video Date:</label>
					<input  style="border-radius:25px;"type="datetime-local" name="video_date" value="<?php echo $row2['video_date'];?>" required><br>
					<label style="margin-left:5%;font-size:20px;">Video Description:</label>
					<br>
					<textarea style="border-radius:25px;margin-left:5%;font-size:20px;" cols="40" rows="4" name="video_details" required><?php echo $row2['video_details']; ?></textarea>
					<br>
					<select style="margin-left:5%;font-size:20px;" name="video_status" id="cars">
					  <option value="Online">Online</option>
					  <option value="Offline">Offline</option>
					  
					</select>
					<br>
					<label style="margin-left:5%;font-size:20px;">Video Picture:</label>
					<input type="file" name="choosefile" />
					<br>
					<label style="margin-left:5%;font-size:20px;">Video file:</label>
					<input type="file" name="choosefile2" />
					<br>
					<br><input style="margin-left:5%;" class="button" type="submit" name="editbtn" value="UPDATE!"/>
					
					<?php
				if(isset($_POST["editbtn"])) {
					$video_name = trim($_POST["video_name"]);
					$video_price = $_POST["video_price"];
					$video_details = $_POST["video_details"];
					$video_status = $_POST["video_status"];

					// Format the date properly
					$video_date_input = $_POST["video_date"];
					$datetime = new DateTime($video_date_input);
					$video_date = $datetime->format('Y-m-d H:i:s');

					// Fetch existing video data
					$result = mysqli_query($connect, "SELECT * FROM video WHERE video_id='$video_id'");
					$row2 = mysqli_fetch_assoc($result);

					// Handling Empty File Upload for Image
					if (!empty($_FILES['choosefile']['name'])) {
						$video_img = time() . '_' . $_FILES['choosefile']['name'];
						move_uploaded_file($_FILES['choosefile']['tmp_name'], 'images/' . $video_img);
					} else {
						$video_img = isset($row2['video_img']) ? $row2['video_img'] : '';  // Corrected
					}

					// Handling Empty File Upload for Video
					if (!empty($_FILES['choosefile2']['name'])) {
						$video_file = time() . '_' . $_FILES['choosefile2']['name'];
						move_uploaded_file($_FILES['choosefile2']['tmp_name'], 'video/' . $video_file);
					} else {
						$video_file = isset($row2['video_file']) ? $row2['video_file'] : '';  // Corrected
					}

					// Update query (Ensure correct table name)
					$update_query = "UPDATE video SET  
										video_name='$video_name',
										video_price='$video_price',
										video_details='$video_details',
										video_status='$video_status',
										video_date='$video_date',
										video_img='$video_img',
										video_file='$video_file'
									 WHERE video_id='$video_id'";

					if (mysqli_query($connect, $update_query)) {
						echo "<script>
								Swal.fire({
									icon: 'success',
									title: 'Success!',
									text: 'Video updated successfully!',
									confirmButtonText: 'OK'
								}).then((result) => {
									if (result.isConfirmed) {
										window.location.href = 'video_list.php';
									}
								});
							  </script>";
					} else {
						echo "<script>alert('Error updating video: " . mysqli_error($connect) . "');</script>";
					}
				}
}

				?>


					</form>
              </div>
            </div>
          </div>
        </div>
      </div>
     
      
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-symbols-rounded py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-symbols-rounded">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark active" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        
       
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>