<?php
include("dataconnection.php");
session_start();

require_once __DIR__ . '/vendor/autoload.php';

// Set PDF to Landscape mode
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4-L', // A4 size in Landscape orientation
    'default_font' => 'Arial'
]);

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

    // Fetch user details
    $resultuser = mysqli_query($connect, "SELECT * FROM user WHERE user_id='$user_id'");
    if ($resultuser && mysqli_num_rows($resultuser) > 0) {
        $usr = mysqli_fetch_assoc($resultuser);
    } else {
        die("User details not found.");
    }

    // Start buffering the output
    ob_start();
    include 'makepdf.php'; // Include the HTML content
    $html = ob_get_clean();

    // Write the HTML content to the PDF
    $mpdf->WriteHTML($html);


    // Output the PDF as a downloadable file
    $mpdf->Output('receipt.pdf', 'D');
    exit;
}
?>
