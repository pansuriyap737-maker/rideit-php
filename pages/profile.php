<?php
session_start();
include('../config.php');
include('uder_index.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$success = "";
$error = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
    $newName = mysqli_real_escape_string($conn, trim($_POST['name']));
    $newEmail = mysqli_real_escape_string($conn, trim($_POST['email']));

    $updateSql = "UPDATE users SET name='$newName', email='$newEmail' WHERE id=$userId";
    if (mysqli_query($conn, $updateSql)) {
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_password'])) {
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    $fetchUser = mysqli_query($conn, "SELECT password FROM users WHERE id=$userId");
    $userData = mysqli_fetch_assoc($fetchUser);

    if (!password_verify($currentPassword, $userData['password'])) {
        $error = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePwdSql = "UPDATE users SET password='$hashedPassword' WHERE id=$userId";
        if (mysqli_query($conn, $updatePwdSql)) {
            $success = "Password updated successfully!";
        } else {
            $error = "Failed to update password.";
        }
    }
}

// Refresh info
$result = mysqli_query($conn, "SELECT name, email, role FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Profile</title>
    <style>
        /* Importing font from React CSS */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        /* Styles from your React Myprofile.css with adjustments for label layout */
        .profile {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        #profile-heading,
        #change-password {
            text-align: center;
            font-family: "Poppins", sans-serif;
            margin-bottom: 20px;
        }

        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            font-family: "Poppins", sans-serif;
        }

        .profile-update {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            width: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-right: 30px;
        }

        label {
            display: block;
            /* Ensure labels start on a new line */
            margin-bottom: 5px;
            /* Add space between label and input */
            font-family: "Poppins", sans-serif;
            font-size: 16px;
            /* Match font size for consistency */
            font-weight: 600;
        }

        #update-btn {
            margin: 30px 0 20px;
            height: 43px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            background-color: green;
            color: white;
            width: 100%;
            transition: 0.3s;
            font-family: "Poppins", sans-serif;
        }

        #update-btn:hover {
            transform: scale(105%);
            background-color: #8000ff;
            color: white;
            font-family: "Poppins", sans-serif;
        }

        #update-name,
        #update-email,
        #current-password,
        #new-password,
        #confirm-new-password {
            width: 400px;
            height: 43px;
            font-size: 16px;
            font-family: "Poppins", sans-serif;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 10px;
            /* Ensure space between inputs */
        }

        .password-update {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            width: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-left: 30px;
        }

        #logout-btn {
            margin: 30px 0 20px;
            height: 43px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            background-color: green;
            color: white;
            width: 10%;
            transition: 0.3s;
        }

        #logout-btn:hover {
            transform: scale(103%);
            background-color: #8000ff;
            color: white;
        }

        /* Additional styles for success/error messages */
        .success,
        .error {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
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
    <div class="profile">
        <div class="profile-container">
            <div class="profile-update">
                <h1 id="profile-heading">My Profile</h1>
                <?php if ($success): ?>
                    <div class="success"><?php echo $success; ?></div><?php endif; ?>
                <?php if ($error): ?>
                    <div class="error"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="update_profile" value="1">
                    <label htmlFor="update-name">Name:</label>
                    <input type="text" id="update-name" name="name"
                        value="<?php echo htmlspecialchars($user['name']); ?>" maxlength="12" required>
                    <label htmlFor="update-email">Email:</label>
                    <input type="email" id="update-email" name="email"
                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <button type="submit" id="update-btn">Update Profile</button>
                </form>
            </div>

            <div class="password-update">
                <h1 id="change-password">Change Password</h1>
                <?php if ($success): ?>
                    <div class="success"><?php echo $success; ?></div><?php endif; ?>
                <?php if ($error): ?>
                    <div class="error"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="update_password" value="1">
                    <label htmlFor="current-password">Current Password:</label>
                    <input type="password" id="current-password" name="current_password" required>
                    <label htmlFor="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new_password" required>
                    <label htmlFor="confirm-new-password">Confirm New Password:</label>
                    <input type="password" id="confirm-new-password" name="confirm_password" required>
                    <button type="submit" id="update-btn">Update Password</button>
                </form>
            </div>
        </div>
        <button id="logout-btn" onclick="window.location.href='logout.php'">Log Out</button>
    </div>
    <div class="custom-footer">
    <?php include('../includes/footer.php'); ?>
</div>
</body>
</html>