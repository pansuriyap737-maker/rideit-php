<?php
session_start();
include('../config.php');
include('uder_index.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$userName = $_SESSION['user_name'];



?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
    body { font-family: 'Poppins', sans-serif; background:#f6f7fb; margin:0; }
    .dashboard-container { max-width: 1200px; margin: 110px auto 40px; padding: 0 16px; }
    .welcome { text-align: center; margin-bottom: 18px; font-size: 26px; font-weight:600; }
    .subtext { text-align:center; color:#667085; margin-bottom: 26px; }

    .metric-row { display:flex; gap:18px; margin-bottom: 20px; flex-wrap: wrap; }
    .metric { flex:1; min-width: 240px; background:#fff; border-radius:14px; padding:16px; box-shadow:0 8px 24px rgba(0,0,0,0.06); display:flex; align-items:center; justify-content:space-between; }
    .metric .title { color:#667085; font-weight:600; }
    .metric .value { font-size: 28px; font-weight:700; color:#1d2939; }

    .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; }
    .card { background:#fff; padding:18px; border-radius:14px; box-shadow:0 8px 24px rgba(0,0,0,0.06); text-align:center; }
    .card h3 { margin: 6px 0 8px; font-size: 18px; color:#6a0fe0; font-weight:600; }
    .card p { font-size: 26px; font-weight: 700; color: #1d2939; }

    .links { text-align:center; margin-top: 28px; }
    .btn-link { display:inline-block; margin: 0 8px; padding:10px 14px; border-radius:10px; border:2px solid #6a0fe0; color:#6a0fe0; text-decoration:none; font-weight:600; background:#fff; }
    .btn-link:hover { background:#f3e9ff; }

    .custom-footer { background-color: #6a0fe0; color: white; text-align: center; padding: 15px 0; margin-top: 40px; }
</style>

<div class="dashboard-container">
    <div class="welcome">👋 Welcome, <strong><?php echo ucfirst($userName); ?></strong>!</div>
    <div class="subtext">Here’s a quick snapshot of your account and activity.</div>

    <div class="metric-row">
        <div class="metric">
            <div class="title">Total Cars</div>
            <div class="value">52</div>
        </div>
        <div class="metric">
            <div class="title">Cities Covered</div>
            <div class="value">20</div>
        </div>
        <div class="metric">
            <div class="title">Your Trips</div>
            <div class="value">7</div>
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Profile</h3>
            <p>Keep your information up to date.</p>
            <a class="btn-link" href="profile.php">Manage Profile</a>
        </div>
        <div class="card">
            <h3>My Bookings</h3>
            <p>Track your active and past bookings.</p>
            <a class="btn-link" href="user_payment.php">View Bookings</a>
        </div>
        <div class="card">
            <h3>Explore Rides</h3>
            <p>Find new rides that match your route.</p>
            <a class="btn-link" href="trip_booking.php">Book a Ride</a>
        </div>
    </div>

    <div class="links">
        <a class="btn-link" href="profile.php">My Profile</a>
        <a class="btn-link" href="user_payment.php">My Bookings</a>
        <a class="btn-link" href="logout.php">Logout</a>
    </div>
</div>

<div class="custom-footer">
    <?php include('../includes/footer.php'); ?>
</div>
