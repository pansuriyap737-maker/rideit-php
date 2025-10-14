<?php
session_start();
include('../config.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Delete image file if exists
    $image_result = mysqli_query($conn, "SELECT car_image FROM cars WHERE car_id = $car_id");
    $image = mysqli_fetch_assoc($image_result);
    if ($image && file_exists("../uploads/" . $image['car_image'])) {
        unlink("../uploads/" . $image['car_image']);
    }

    // Delete car from DB
    mysqli_query($conn, "DELETE FROM cars WHERE car_id = $car_id");
}

header("Location: manage_cars.php");
exit;
