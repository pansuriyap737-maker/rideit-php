<?php
session_start();
include('../config.php');

// Ensure the car_id is passed and it's valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $car_id = $_GET['id'];
    
    // Start a transaction to make sure both delete operations succeed or fail together
    mysqli_begin_transaction($conn);

    try {
        // First, delete the payments associated with this car_id
        $delete_payments_query = "DELETE FROM payments WHERE car_id = $car_id";
        mysqli_query($conn, $delete_payments_query);
        
        // Then, delete the car
        $delete_car_query = "DELETE FROM cars WHERE car_id = $car_id";
        mysqli_query($conn, $delete_car_query);

        // Commit the transaction
        mysqli_commit($conn);
        
        echo "<script>alert('Trip and associated payments deleted successfully!'); window.location.href = 'add_trip.php';</script>";
    } catch (Exception $e) {
        // If any error occurs, rollback the transaction
        mysqli_roll_back($conn);
        echo "<script>alert('Error deleting trip: " . $e->getMessage() . "');</script>";
    }
} else {
    echo "<script>alert('Invalid car ID.'); window.location.href = 'add_trip.php';</script>";
}
?>
