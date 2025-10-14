<?php
session_start();
include('uder_index.php');  // Assuming this is correct; adjust if needed
include('../config.php');

// Get unique pickup and drop locations (though not used directly now, keeping for reference if needed)
$pickup_result = mysqli_query($conn, "SELECT DISTINCT pickup_location FROM cars");
$drop_result = mysqli_query($conn, "SELECT DISTINCT drop_location FROM cars");

// Handle filters
$pickup = $_GET['pickup'] ?? '';
$drop = $_GET['drop'] ?? '';
$date = $_GET['date'] ?? '';

$conditions = [];

if (!empty($pickup)) {
    $conditions[] = "pickup_location LIKE '%" . mysqli_real_escape_string($conn, $pickup) . "%'";
}
if (!empty($drop)) {
    $conditions[] = "drop_location LIKE '%" . mysqli_real_escape_string($conn, $drop) . "%'";
}
if (!empty($date)) {
    $conditions[] = "DATE(date_time) = '" . mysqli_real_escape_string($conn, $date) . "'";
}

$where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
$query = "
    SELECT 
        c.*, COALESCE(c.driver_name, d.name) AS driver_name,
        COALESCE(SUM(CASE WHEN p.payment_status = 'success' THEN 1 ELSE 0 END), 0) AS booked_seats
    FROM 
        cars c
    LEFT JOIN drivers d ON d.id = c.user_id
    LEFT JOIN 
        payments p ON c.car_id = p.car_id
    $where
    GROUP BY 
        c.car_id
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Your Trip</title>
    <style>
        /* Importing font from React CSS */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        /* Styles from your React Bookrides.css */
        .rides-container {
            margin-top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        #rides-search {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            align-items: center;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            padding: 10px;
        }

        .location {
            margin: 10px;
            height: 50px;
            width: 210px;
            border: 0.5px solid #6a0fe0;
            border-radius: 10px;
            padding: 6px;
            font-size: 16px;
        }

        #search-rides {
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
            width: 100px;
            height: 45px;
            font-size: 17px;
            margin-left: 10px;
        }

        #search-rides:hover {
            transform: scale(105%);
            background-color: #8000ff;
            color: white;
        }

        .rides-info {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            width: 100%;
            margin-top: 30px;
        }

        .rides-card {
            width: 100%;
            height: auto;
            display: flex;
            justify-content: center;
            flex-direction: column;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background: #fff;
        }

        .car-photos {
            height: 70%;
            width: 100%;
            padding: 5px;
            border-radius: 20px;
        }

        #car-title {
            margin-top: -5px;
            font-weight: 600;
            margin-left: 10px;
        }

        #pickup-loc {
            margin-left: 10px;
        }

        #drop-loc {
            margin-left: 10px;
        }

        #capacity {
            margin-left: 10px;
        }

        #pricetag {
            margin-left: 10px;
        }

        #driver-name {
            margin-left: 10px;
        }

        #date-time {
            margin-left: 10px;
        }

        #ride-book {
            margin: 7px auto;
            width: 90%;
            height: 35px;
            font-size: 15px;
            border: none;
            border-radius: 10px;
            color: white;
            background-color: green;
            transition: 0.3s;
        }

        #ride-book:hover {
            background-color: #8000ff;
            transform: scale(105%);
        }

        /* Existing styles adjusted */
        h2 {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 100px;
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
    <div class="rides-container">
        
        <form method="GET" id="rides-search">
            <input type="text" name="pickup" class="location" placeholder="Pick Up" value="<?= htmlspecialchars($pickup ?? '') ?>">

            <input type="text" name="drop" class="location" placeholder="Drop" value="<?= htmlspecialchars($drop ?? '') ?>">

            <input type="date" name="date" class="location" value="<?= htmlspecialchars($date ?? '') ?>">

            <button type="submit" id="search-rides">Search</button>
        </form>

        <div class="rides-info">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($car = mysqli_fetch_assoc($result)): ?>
                    <?php $available = $car['seating'] - $car['booked_seats']; ?>
                    <div class="rides-card">
                        <img src="../uploads/<?= htmlspecialchars($car['car_image']) ?>" alt="Car Image" class="car-photos">
                        <h2 id="car-title"><?= htmlspecialchars($car['car_name']) ?></h2>
                        <p id="driver-name">Driver Name: <b><?= htmlspecialchars($car['driver_name'] ?? 'N/A') ?></b></p>
                        <p id="pickup-loc">Pickup: <b><?= htmlspecialchars($car['pickup_location']) ?></b></p>
                        <p id="drop-loc">Drop: <b><?= htmlspecialchars($car['drop_location']) ?></b></p>
                        <p id="date-time">DateTime: <b><?= date('d M Y, h:i A', strtotime($car['date_time'])) ?></b></p>
                        <h3 id="pricetag">₹<?= number_format($car['amount']) ?></h3>
                        <p id="capacity">Seating Capacity: <b><?= $car['seating'] ?? '-' ?></b></p>
                        <?php if ($available > 0): ?>
                            <button id="ride-book">Book Now</button>
                        <?php else: ?>
                            <div style="color:red; font-weight:bold; margin-top:10px;">Fully Booked</div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center;">No cars found for the selected filters.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="custom-footer">
        <?php include('../includes/footer.php'); ?>
    </div>
</body>
</html>
