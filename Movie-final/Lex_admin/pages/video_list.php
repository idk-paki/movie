<?php
include("dataconnection.php");
session_start();

if (isset($_GET["delete"])) {
    $delete = $_GET["delete"];

    // Validate that video_id is numeric to avoid SQL injection or errors
    if (!is_numeric($delete)) {
        echo "<script>
                alert('Invalid video ID!');
                window.location.href='video_list.php';
              </script>";
        exit();
    }

    // Execute delete query
    $result = mysqli_query($connect, "DELETE FROM video WHERE video_id='$delete'");

    // Use mysqli_affected_rows to check deletion
    if (!$result || mysqli_affected_rows($connect) == 0) {
        echo "<script>
                alert('Video not found or could not be deleted!');
                window.location.href='video_list.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Video deleted successfully!');
                window.location.href='video_list.php';
              </script>";
        exit();
    }
}

if (!isset($_SESSION['admin_id'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video List</title>
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
   Video List
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
            window.location.href = `video_list_edit.php?edit=${videoId}`;
        }
    });
}

function confirmation2(videoId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You are about to delete this video!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to PHP delete handler
            window.location.href = `video_list.php?delete=${videoId}`;
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
        <span class="ms-1 text-sm text-dark">Admin (Happy Cinema)</span>
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
          <a class="nav-link text-dark" href="order_manage.php">
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Video List</li>
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
                <h6 class="text-white text-capitalize ps-3">Video List</h6>
				
				<form name="search_form" method="GET" action="">
			<div>
			<input class="border" style="margin-left:10px;" type="text" name="searchname" placeholder="Video Name">

			<input class="button" type="submit" value="Search" name="searchbtn">
			</div>

			</form>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="align-middle text-center">Video Name</th>
					  <th class="align-middle text-center">Video Price</th>
					  <th class="align-middle text-center">Video Status</th>
					  <th class="align-middle text-center">Video Details</th>
                      <th class="align-middle text-center">Video DateTime</th>
                      <th class="align-middle text-center">Action</th>
					 
                    </tr>
                  </thead>
				  <?php
				  if(isset($_GET["searchbtn"]))
					{
						$result=$_GET["searchname"];
						$search=mysqli_query($connect,"SELECT * from video WHERE video_name like '%$result%'  Order BY video_date  DESC ");
						if(mysqli_num_rows($search)==0)
						{
							?>
							
							
							<tbody>
								  <tr>
									 <td class="align-middle text-center">
										<?php echo " Result could not be found !"; ?>
									  </td>
									</tr>
								  </tbody>
						<?php
						}
						else
						{
						while($row=mysqli_fetch_assoc($search))
						{
						?>
                  <tbody>
                    <tr>
                      <td class="align-middle text-center">
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="images/<?php echo  $row['video_img']; ?>" style="height:150px;width:100px;"  alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 style="word-wrap: break-word; white-space: normal; max-width: 200px;"class="mb-0 text-sm"><?php echo  $row['video_name']; ?></h6>
                            
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                        <p  style="font-size: 30px;">RM <?php echo  $row['video_price']; ?></p>
                        
                      </td>
                      <td class="align-middle text-center text-sm">
                       
						<?php
						$status=$row['video_status'];
						if($status="Online")
						{
							echo " <span class='badge badge-sm bg-gradient-success'>Online</span>";
						}else
						{
							echo "<span class='badge badge-sm bg-gradient-secondary'>Offline</span>";
						}
						?>
						
                      </td>
					  <td class="align-middle text-center" style="word-wrap: break-word; white-space: normal; max-width: 200px;">
						<span class="text-secondary text-xs font-weight-bold"><?php echo  $row['video_details']; ?></span>
					</td>

                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?php echo  $row['video_date']; ?></span>
                      </td>
					  
                      
						<td class="align-middle text-center">
                        <button onclick="confirmation(<?php echo $row['video_id']; ?>)">Edit</button>
						<button onclick="confirmation2(<?php echo $row['video_id']; ?>)">Delete</button>
                      </td>
                      
                    </tr>
                   
                   
                    </tr>
                  </tbody>
				  			  <?php
						}
						}
					}else
					{
						$nosearch=mysqli_query($connect,"SELECT * from video Order BY video_date DESC ");
						while($row=mysqli_fetch_assoc($nosearch))
						{
						?>
					<tbody>
                    <tr>
                      <td class="align-middle text-center">
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="images/<?php echo  $row['video_img']; ?>" style="height:150px;width:100px;"  alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 style="word-wrap: break-word; white-space: normal; max-width: 200px;"class="mb-0 text-sm"><?php echo  $row['video_name']; ?></h6>
                            
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                        <p  style="font-size: 30px;">RM <?php echo  $row['video_price']; ?></p>
                        
                      </td>
                      <td class="align-middle text-center text-sm">
                       
						<?php
						 $status = $row['video_status'];
						if ($status = "Online") {  // Use '==' for comparison
							echo " <span class='badge badge-sm bg-gradient-success'>Online</span>";
						} else {
							echo "<span class='badge badge-sm bg-gradient-secondary'>Offline</span>";
						}
					?>
						
                      </td>
					  <td class="align-middle text-center" style="word-wrap: break-word; white-space: normal; max-width: 200px;">
						<span class="text-secondary text-xs font-weight-bold"><?php echo  $row['video_details']; ?></span>
					</td>

                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?php echo  $row['video_date']; ?></span>
                      </td>
					  
                      
						<td class="align-middle text-center">
                        <button onclick="confirmation(<?php echo $row['video_id']; ?>)">Edit</button>
						<button onclick="confirmation2(<?php echo $row['video_id']; ?>)">Delete</button>
                      </td>
                      
                    </tr>
                   
                   
                    </tr>
                  </tbody>
						<?php
						}
					}
						?>
	
                </table>
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