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
.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    margin-top:120px;
    height: 50vh;
}

.welcome {
    text-align: center;
    margin-bottom: 40px;
    font-size: 24px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}

.card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.card h3 {
    margin: 10px 0;
    font-size: 20px;
    color: #007bff;
}

.card p {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.links {
    text-align: center;
    margin-top: 40px;
}

.links a {
    margin: 0 10px;
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.custom-footer {
    background-color: #6a0fe0;
    color: white;
    text-align: center;
    padding: 15px 0;
    margin-top: 40px;
}
</style>

<div class="dashboard-container">
    <div class="welcome">
        👋 Welcome, <strong><?php echo ucfirst($userName); ?></strong>!
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Total Cars</h3>
            <p>52</p>
        </div>
        <div class="card">
            <h3>Cities Covered</h3>
            <p>20</p>
        </div>
        <div class="card">
            <h3>Ratings</h3>
            <p>A+</p>
        </div>
        <div class="card">
            <h3>Your Trips</h3>
            <p>7</p>
        </div>
    </div>

    <div class="links">
        <a href="profile.php">My Profile</a> |
        <a href="bookings.php">My Bookings</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="custom-footer">
    <?php include('../includes/footer.php'); ?>
</div>
