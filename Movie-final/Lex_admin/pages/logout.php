<?php
include("dataconnection.php");
session_start();

unset($_SESSION["admin_id"]);
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    // make sure the page full load b4 to run
    window.onload = function() {
        Swal.fire({
            icon: 'success',
            title: 'Logged Out',
            text: 'You have successfully logged out!'
        }).then(() => {
            
            window.location.href = 'sign-in.php'; 
        });
    };
</script>

<!-- when js no working, will auto jump back to index -->
<noscript>
    <meta http-equiv="refresh" content="2;url=sign-in.php">
</noscript>

</body>
</html>
