<?php
session_start();
include('uder_index.php'); // Include header or navigation
include('../config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fixed SQL query (removed phone column)
$query = "
    SELECT 
        u.name AS driver_name,
        c.number_plate AS car_number_plate,
        c.pickup_location AS pickup,
        c.drop_location AS drop_location,
        p.amount AS amount,
        p.payment_status,
        p.payment_date
    FROM 
        payments p
    INNER JOIN 
        cars c ON p.car_id = c.car_id
    INNER JOIN 
        pessanger u ON c.user_id = u.id
    WHERE 
        c.user_id = $user_id AND p.user_id != $user_id
    ORDER BY 
        p.payment_date DESC
";

$result = mysqli_query($conn, $query);
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
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
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
