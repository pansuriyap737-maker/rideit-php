<?php
session_start();
include('driver_index.php');
include('../config.php'); // Assuming this has your database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pending Rides</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .pending-rides-container {
            width: 1400px;
            margin: 40px auto 40px;
            font-family: Arial, sans-serif;
            height: 100vh;
        }

        .pending-rides-heading {
            font-size: 30px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* Important to avoid double borders */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #c5c4c4ff;
            /* Add borders to all sides */
        }

         tr:hover {
            background-color: #e9e7e7ff;
        }

        th {
            font-size: 17px;
            background-color: #6a0fe0;
            color: white;
        }

        td {
            font-size: 16px;
            align-items: center;
        }

        .ride-accept {
            padding: 5px;
            font-size: 15px;
            border: 2px solid #6a0fe0;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(58, 58, 58, 0.1);
            color: #6a0fe0;
            transition: 0.3s;
            width: 90px;
            margin-bottom: 10px;
        }

        .ride-accept:hover {
            transform: scale(105%);
        }

        .ride-cancel {
            padding: 5px;
            font-size: 15px;
            border: 2px solid #ff0000;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(58, 58, 58, 0.1);
            color: #ff0000;
            transition: 0.3s;
            width: 90px;
        }

        .ride-cancel:hover {
            transform: scale(105%);
        }

        .action-rides-pending {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
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
    <div class="pending-rides-container">
        <h2 class="pending-rides-heading">Pending Rides</h2>
        <table>
            <thead>
                <tr>
                    <th>Full Name(Passenger)</th>
                    <th>Number Plate</th>
                    <th>Pickup</th>
                    <th>Drop</th>
                    <th>Booking DateTime</th>
                    <th>Amount (₹)</th>
                    <th>Payment Mode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>Vatsal Sabhaya</td>
                        <td>GJ05FS2345</td>
                        <td>Jakatnaka</td>
                        <td>Pasodara</td>
                        <td>03/04/2006</td>
                        <td>10000</td>
                        <td>Online/Cod</td>
                        <td class="action-rides-pending">
                            <button class="ride-accept">Accept</button>
                            <button class="ride-cancel">Cancel</button>
                        </td>
                    </tr>
                
            </tbody>
        </table>
    </div>

    <div class="custom-footer">
        <?php include('../includes/footer.php'); ?>
    </div>
</body>
</html>
