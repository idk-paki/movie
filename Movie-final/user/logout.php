<?php
include("dataconnection.php");
session_start();

unset($_SESSION["user_id"]);
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
            
            window.location.href = 'index.php'; 
        });
    };
</script>

<!-- when js no working, will auto jump back to index -->
<noscript>
    <meta http-equiv="refresh" content="2;url=index.php">
</noscript>

</body>
</html>
