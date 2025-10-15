<?php
session_start();
include('uder_index.php'); // Include header or navigation
include('../config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)($_SESSION['user_id'] ?? 0);
$session_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$view = isset($_GET['view']) ? strtolower(trim($_GET['view'])) : 'active';
if (!in_array($view, ['active','completed','canceled'], true)) { $view = 'active'; }

// Ensure users table exists (in case only `pessanger` was used earlier)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    contact VARCHAR(15) DEFAULT NULL,
    password VARCHAR(255) DEFAULT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Map current passenger to a users.id used in payments FK
$userIdForFk = 0;
$chkUsers = mysqli_query($conn, "SELECT id FROM users WHERE id = $user_id");
if ($chkUsers && mysqli_num_rows($chkUsers) === 1) {
    $userIdForFk = (int)mysqli_fetch_assoc($chkUsers)['id'];
} else {
    $ps = mysqli_query($conn, "SELECT email FROM pessanger WHERE id = $user_id");
    if ($ps && mysqli_num_rows($ps) === 1) {
        $emailRow = mysqli_fetch_assoc($ps);
        $emailEsc = mysqli_real_escape_string($conn, (string)$emailRow['email']);
        $u2 = mysqli_query($conn, "SELECT id FROM users WHERE email = '$emailEsc'");
        if ($u2 && mysqli_num_rows($u2) === 1) {
            $userIdForFk = (int)mysqli_fetch_assoc($u2)['id'];
        }
    }
}

// Fixed SQL query (removed phone column)
$activeQ = "
    SELECT p.payment_id, COALESCE(p.driver_name, d.name) AS driver_name,
           COALESCE(p.car_number_plate, c.number_plate) AS car_number_plate,
           COALESCE(p.pickup, c.pickup_location) AS pickup,
           COALESCE(p.drop_location, c.drop_location) AS drop_location,
           p.amount AS amount, COALESCE(p.payment_mode, 'ONLINE') AS payment_mode,
           p.payment_status, p.payment_date, p.ride_status
    FROM payments p
    INNER JOIN cars c ON p.car_id = c.car_id
    LEFT JOIN drivers d ON d.id = c.user_id
    WHERE p.user_id = $userIdForFk AND p.payment_status='Success' AND p.ride_status IN ('pending','active')
    ORDER BY p.payment_date DESC";

$completedQ = "
    SELECT p.payment_id, COALESCE(p.driver_name, d.name) AS driver_name,
           COALESCE(p.car_number_plate, c.number_plate) AS car_number_plate,
           COALESCE(p.pickup, c.pickup_location) AS pickup,
           COALESCE(p.drop_location, c.drop_location) AS drop_location,
           p.amount AS amount, COALESCE(p.payment_mode, 'ONLINE') AS payment_mode,
           p.payment_status, p.payment_date, p.ride_status
    FROM payments p
    INNER JOIN cars c ON p.car_id = c.car_id
    LEFT JOIN drivers d ON d.id = c.user_id
    WHERE p.user_id = $userIdForFk AND p.payment_status='Success' AND p.ride_status='completed'
    ORDER BY p.payment_date DESC";

$canceledQ = "
    SELECT p.payment_id, COALESCE(p.driver_name, d.name) AS driver_name,
           COALESCE(p.car_number_plate, c.number_plate) AS car_number_plate,
           COALESCE(p.pickup, c.pickup_location) AS pickup,
           COALESCE(p.drop_location, c.drop_location) AS drop_location,
           p.amount AS amount, COALESCE(p.payment_mode, 'ONLINE') AS payment_mode,
           p.payment_status, p.payment_date, p.ride_status
    FROM payments p
    INNER JOIN cars c ON p.car_id = c.car_id
    LEFT JOIN drivers d ON d.id = c.user_id
    WHERE p.user_id = $userIdForFk AND p.payment_status='Success' AND p.ride_status='canceled'
    ORDER BY p.payment_date DESC";

$activeRes = mysqli_query($conn, $activeQ);
$completedRes = mysqli_query($conn, $completedQ);
$canceledRes = mysqli_query($conn, $canceledQ);

// Totals
$totalsSql = "SELECT 
  SUM(CASE WHEN p.ride_status='completed' THEN 1 ELSE 0 END) AS total_trips,
  SUM(CASE WHEN p.ride_status='completed' THEN p.amount ELSE 0 END) AS total_amount
FROM payments p WHERE p.user_id = $userIdForFk AND p.payment_status='Success'";
$totals = mysqli_fetch_assoc(mysqli_query($conn, $totalsSql));

// no single query now; we use view-specific result sets
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Car Bookings</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .payment-container {
            height: 80vh;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .userpanel-tabel {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .user-payment,
        .user-ride-data {
            font-size: 16px;
            padding: 11px 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .user-payment {
            background-color: #8000ff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        #payment-heading {
            margin-top: 20px;
            font-weight: 600;
        }

        .cancel-passenger {
            padding: 6px 12px;
            font-size: 15px;
            border: 2px solid red;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(58, 58, 58, 0.1);
            color: rgb(255, 0, 0);
            transition: 0.3s;
            cursor: pointer;
        }

        .cancel-passenger:hover {
            transform: scale(1.05);
        }

         .custom-footer {
            background-color: #6a0fe0;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1 id="payment-heading">My Bookings</h1>
        <table class="userpanel-tabel">
            <tr>
                <th class="user-payment">Full Name (Driver)</th>
                <th class="user-payment">Car No. Plate</th>
                <th class="user-payment">Pick-up</th>
                <th class="user-payment">Drop</th>
                <th class="user-payment">Amount</th>
                <th class="user-payment">Phone No.</th>
                <th class="user-payment">Payment Mode</th>
                <th class="user-payment">Action</th>
            </tr>
            <?php if ($activeRes && mysqli_num_rows($activeRes) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($activeRes)): ?>
                    <tr>
                        <td class="user-ride-data"><?= htmlspecialchars($row['driver_name']) ?></td>
                        <td class="user-ride-data"><?= htmlspecialchars($row['car_number_plate']) ?></td>
                        <td class="user-ride-data"><?= htmlspecialchars($row['pickup']) ?></td>
                        <td class="user-ride-data"><?= htmlspecialchars($row['drop_location']) ?></td>
                        <td class="user-ride-data">₹<?= number_format($row['amount'], 2) ?></td>
                        <td class="user-ride-data">7862893655</td>
                        <td class="user-ride-data">COD/ONLINE</td>
                        <td class="user-ride-data"><button class="cancel-passenger">Cancel</button></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="user-ride-data">No bookings found.</td></tr>
            <?php endif; ?>
        </table>
    </div>

       <div class="custom-footer">
    <?php include('../includes/footer.php'); ?>
</div>
</body>
</html>
