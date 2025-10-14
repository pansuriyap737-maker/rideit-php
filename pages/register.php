<?php
include('../includes/header.php');
include('../config.php');

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validations
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username exists
        $check_name = mysqli_query($conn, "SELECT * FROM users WHERE name = '$name'");
        if (mysqli_num_rows($check_name) > 0) {
            $error = "Username already taken. Please choose another.";
        } else {
            // Check if email exists
            $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
            if (mysqli_num_rows($check_email) > 0) {
                $error = "Email already registered.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into DB
                $insert = mysqli_query($conn, "INSERT INTO users (name, email, contact, password, role) VALUES ('$name', '$email', '$contact', '$hashed_password', 'user')");

                if ($insert) {
                    $success = "Registration successful! Redirecting to <a href='login.php'>Login</a>...";
                    echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 3000);</script>";
                } else {
                    $error = "Something went wrong. Try again.";
                }
            }
        }
    }
}
?>

<style>
        *{
            font-family: poppins;
        }
       body, html {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
        width: 600px;
    }

    footer {
        margin-top: auto;
    }

    .login-container {
        width: 500px;
        margin: 70px auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: -20px;
        font-weight: bold;
        text-align: left;
    }

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 9px;
        cursor: pointer;
        color: #555;
    }

    .form-control {
        width: 100%;
        height: 45px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .submit-btn {
        padding: 10px 20px;
        background-color: green;
        color: white;
        border: none;
        border-radius: 5px;
        width: 100%;
        cursor: pointer;
    }

    .submit-btn:hover {
         transform: scale(105%);
    background-color: #8000ff;
    color: white;
    }
</style>

<main style="max-width: 450px; margin: 50px auto;" class="login-container">
    <h2 style="text-align: center; margin-bottom:20px;">Register</h2>

    <?php if ($error): ?>
        <div style="color: red; text-align: center; margin-bottom: 10px;"><?php echo $error; ?></div>
    <?php elseif ($success): ?>
        <div style="color: green; text-align: center; margin-bottom: 10px;"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 15px;">
            <label>Username:</label><br>
            <input type="text" name="name" required class="form-control" maxlength="12" placeholder="FirstName LastName">

        </div>

        <div style="margin-bottom: 15px;">
            <label>Email:</label><br>
            <input type="email" name="email" required class="form-control" placeholder="Enter your Email">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Contact:</label><br>
            <input type="text" name="contact" required class="form-control" pattern="[0-9]{10}" maxlength="10" placeholder="Enter 10 digit Mobile No.">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Password:</label><br>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required class="form-control" placeholder="Enter your Password">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Confirm Password:</label><br>
            <div class="password-wrapper">
                <input type="password" name="confirm_password" id="confirm_password" required class="form-control" placeholder="Confirm Password" >
            </div>
        </div>

        <button type="submit" class="submit-btn">Register</button>
        <p style="display: block; text-align: center; margin: 10px;">Or</p>
        <button type="submit" class="submit-btn">Register as a Driver</button>
    </form>

    <p style="margin-top: 15px;">Already have an account? <a href="login.php">Login here</a></p>
</main>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

<?php include('../includes/footer.php'); ?>
