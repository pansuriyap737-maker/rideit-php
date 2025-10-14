<?php
session_start();
include('driver_index.php');
include('../config.php'); // Assuming this has your database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ride Details</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        .ride-list-container {
            width: 1400px;
            margin: 40px auto 40px;
            height: 100vh;
        }

        #ride-list-heading {
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


        th {
            font-size: 16px;
            background-color: #6a0fe0;
            color: white;
        }

        td {
            font-size: 15px;
        }

        tr:hover {
            background-color: #e9e7e7ff;
        }

        td img.car-image {
            width: 80px;
            border-radius: 5px;
            object-fit: cover;
        }

        .no-cars {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 16px;
        }

        .edit-btn-rides {
            padding: 5px;
            font-size: 15px;
            border: 2px solid #6a0fe0;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(58, 58, 58, 0.1);
            color: #6a0fe0;
            transition: 0.3s;
            width: 60px;
            margin-right: 10px;
        }

        .edit-btn-rides:hover {
            transform: scale(105%);
        }

        .delete-btn-rides {
            padding: 5px;
            font-size: 15px;
            border: 2px solid #ff0000;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(58, 58, 58, 0.1);
            color: #ff0000;
            transition: 0.3s;
            width: 60px;
        }

        .delete-btn-rides:hover {
            transform: scale(105%);
        }

        .ride-search {
            width: 250px;
            height: 50px;
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
            border: 0.5px solid gray;
        }

        .ride-search-btn {
            margin-bottom: 20px;
            margin-left: 15px;
            width: 150px;
            height: 40px;
            border-radius: 10px;
            background-color: green;
            color: white;
            border: none;
            transition: 0.3s;
        }

        .ride-search-btn:hover {
            transform: scale(105%);
            background-color: #6a0fe0;
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
    <div class="ride-list-container">
        <h3 id="ride-list-heading">Car List</h3>
        <input type="search" placeholder="Search by Location, Number Plate and Car Name" class="ride-search"
            id="search-input" onkeyup="filterTrips()" />
        <button class="ride-search-btn" type="button" onclick="filterTrips()">Search</button>

        <table id="trips-table"> <!-- ID for JavaScript filtering -->
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Car Name</th>
                    <th>Number Plate</th>
                    <th>Seating Capacity</th>
                    <th>Pickup</th>
                    <th>Drop</th>
                    <th>Booking DateTime</th>
                    <th>Amount (₹)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Car Image</td>
                    <td>Hyundai</td>
                    <td>GJ05TV3451</td>
                    <td>3</td>
                    <td>Jakatnaka</td>
                    <td>Pasodara</td>
                    <td>12/10</td>
                    <td>₹100 </td>
                    <td>
                        <button class="edit-btn-rides"
                            onclick="alert('Edit trip ID: <?php echo $trip['id']; ?>')">Edit</button>
                        <button class="delete-btn-rides"
                            onclick="if(confirm('Are you sure?')) { alert('Delete trip ID: <?php echo $trip['id']; ?>'); }">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function filterTrips() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search-input");
            filter = input.value.toLowerCase();
            table = document.getElementById("trips-table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {  // Start from 1 to skip header
                tr[i].style.display = "none";  // Hide row by default
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {  // Check relevant columns (e.g., 1,2,4,5 for carName, numberPlate, pickup, drop)
                    if (td[j]) {
                        if (j === 1 || j === 2 || j === 4 || j === 5) {  // Indices based on table columns
                            txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";  // Show if match
                                break;  // No need to check further
                            }
                        }
                    }
                }
            }
        }
    </script>
</body>
<div class="custom-footer">
        <?php include('../includes/footer.php'); ?>
    </div>
</html>