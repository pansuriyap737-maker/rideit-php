<?php
session_start();
include('admin_header.php');
include('../config.php'); // Assuming this has your database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Cars</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .car-list-container {
            width: 1400px;
            margin: 40px auto 40px;
            font-family: Arial, sans-serif;
        }

        #car-list-heading {
            font-size: 30px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 18px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td img.car-image {
            width: 80px;
            border-radius: 5px;
            object-fit: cover;
        }

        .delete-link {
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

        .delete-link:hover {
            transform: scale(105%);
        }

        .no-cars {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 16px;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .search-user {
            padding: 8px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .search-user-btn {
            padding: 8px 15px;
            margin-left: 8px;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .search-user-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="car-list-container">
        <h3 id="car-list-heading">Car List</h3>
        <div class="top-bar">
            <form class="search-bar" onsubmit="return false;">
                <input
                    type="text"
                    name="search"
                    placeholder="Search car, plate, driver, pickup or drop"
                    id="search-input"
                    class="search-user"
                />
                <button type="button" onclick="filterTrips()" class="search-user-btn">Search</button>
            </form>
        </div>

        <table id="trips-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Car Name</th>
                    <th>Seating</th>
                    <th>Amount (₹)</th>
                    <th>Number Plate</th>
                    <th>Pickup</th>
                    <th>Drop</th>
                    <th>User</th>
                    <th>Booking DateTime</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                        <tr>
                            <td>Car Image</td>
                            <td>Hyundai</td>
                            <td>4</td>
                            <td>₹100</td>
                            <td>
                                GJ05AB1234
                            </td>
                            <td>Jakatnaka</td>
                            <td>Laskana</td>
                            <td>Ronit Kakadiya</td>
                            <td> 15/10/1025
                            </td>
                            <td>
                                <button class="delete-link">Delete</button>
                            </td>
                        </tr>
            </tbody>
        </table>
    </div>

    <script>
        function filterTrips() {
            var input, filter, table, tr, td, i, j;
            input = document.getElementById("search-input");
            filter = input.value.toLowerCase();
            table = document.getElementById("trips-table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j] && [1, 4, 7, 5, 6].includes(j)) {  // Indices for carName, carNumberPlate, driverName, pickup, drop
                        if (td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function deleteTrip(tripId) {
            if (confirm('Are you sure you want to delete this trip?')) {
                fetch(`/api/trips/${tripId}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Trip deleted successfully!');
                        location.reload();  // Reload to update the list
                    } else {
                        alert(`Failed to delete trip: ${data.message}`);
                    }
                })
                .catch(error => {
                    alert(`Error: ${error.message}`);
                });
            }
        }
    </script>
</body>
</html>
