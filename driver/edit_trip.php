<?php
session_start();
include('uder_index.php');
include('../config.php');

if (!isset($_GET['id'])) {
    header("Location: add_trip.php");
    exit;
}

$car_id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch the existing trip data
$query = "SELECT * FROM cars WHERE car_id = $car_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Trip not found.'); window.location.href = 'add_trip.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result);

// Gujarat cities for dropdown
$gujarat_cities = [
    'Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar',
    'Gandhinagar', 'Junagadh', 'Anand', 'Nadiad', 'Bharuch', 'Mehsana',
    'Morbi', 'Gondal', 'Navsari', 'Vapi', 'Veraval', 'Botad', 'Porbandar'
];

// Seating capacity dropdown values
$seating_options = range(1, 8);

// Limit booking date-time range
$minDate = date('Y-m-d\TH:i'); // Current date and time
$maxDate = date('Y-m-d\T23:59', strtotime('+90 day')); // 90 days from now


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trip</title>
    <style>
        form {
            width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            margin-top: 120px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 6px;
            font-weight: bold;
        }
        input, select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        .submit-row {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Edit Trip</h2>

<form method="POST" action="update_trip.php" enctype="multipart/form-data">

    <input type="hidden" name="car_id" value="<?= $row['car_id'] ?>">

    <div class="form-row">
        <div class="form-group">
            <label>Car Image</label>
            <img src="../uploads/<?= htmlspecialchars($row['car_image']) ?>" width="100" height="60" alt="Car Image">
            <input type="file" name="car_image">
        </div>
        <div class="form-group">
            <label>Car Name</label>
            <input type="text" name="car_name" value="<?= htmlspecialchars($row['car_name']) ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Seating Capacity</label>
            <select name="seating" required>
                <option value="">Select</option>
                <?php foreach ($seating_options as $seat): ?>
                    <option value="<?= $seat ?>" <?= ($seat == $row['seating']) ? 'selected' : '' ?>><?= $seat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>City</label>
            <select name="city" required>
                <option value="">Select City</option>
                <?php foreach ($gujarat_cities as $city): ?>
                    <option value="<?= $city ?>" <?= ($city == $row['city']) ? 'selected' : '' ?>><?= $city ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Amount (₹)</label>
            <input type="number" name="amount" value="<?= htmlspecialchars($row['amount']) ?>" required>
        </div>
        <div class="form-group">
            <label>Number Plate</label>
            <input type="text" name="number_plate" value="<?= htmlspecialchars($row['number_plate']) ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Pickup Location</label>
            <select name="pickup_location" required>
                <option value="">Select Pickup</option>
                <?php foreach ($gujarat_cities as $city): ?>
                    <option value="<?= $city ?>" <?= ($city == $row['pickup_location']) ? 'selected' : '' ?>><?= $city ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Drop Location</label>
            <select name="drop_location" required>
                <option value="">Select Drop</option>
                <?php foreach ($gujarat_cities as $city): ?>
                    <option value="<?= $city ?>" <?= ($city == $row['drop_location']) ? 'selected' : '' ?>><?= $city ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Booking DateTime</label>
            <input type="datetime-local" name="date_time" value="<?= date('Y-m-d\TH:i', strtotime($row['date_time'])) ?>" min="<?= $minDate ?>" max="<?= $maxDate ?>" required>
        </div>
    </div>

    <div class="submit-row">
        <button type="submit">Update Trip</button>
    </div>

</form>

</body>
</html>
