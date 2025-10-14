<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('admin_header.php');
include('../config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Cars</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}    
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background: #007bff; color: #fff; }
        .no { color: #777; padding: 20px; }
        img { width: 80px; border-radius: 6px; object-fit: cover; }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Cars</h2>
    <?php
    $res = mysqli_query($conn, "SELECT c.*, COALESCE(c.driver_name, d.name) AS driver_name FROM cars c LEFT JOIN drivers d ON d.id = c.user_id ORDER BY c.created_at DESC");
    ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Driver</th>
                <th>Car Name</th>
                <th>Number Plate</th>
                <th>Seating</th>
                <th>Pickup</th>
                <th>Drop</th>
                <th>Amount</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($res && mysqli_num_rows($res) > 0): ?>
            <?php while($car = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?php if($car['car_image']) { ?><img src="../uploads/<?php echo htmlspecialchars($car['car_image']); ?>"><?php } ?></td>
                    <td><?php echo htmlspecialchars($car['driver_name'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                    <td><?php echo htmlspecialchars($car['number_plate']); ?></td>
                    <td><?php echo (int)$car['seating']; ?></td>
                    <td><?php echo htmlspecialchars($car['pickup_location']); ?></td>
                    <td><?php echo htmlspecialchars($car['drop_location']); ?></td>
                    <td><?php echo number_format($car['amount'],2); ?></td>
                    <td><?php echo $car['date_time'] ? date('d/m/Y H:i', strtotime($car['date_time'])) : ''; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" class="no">No cars found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
