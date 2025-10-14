<?php
session_start();
include('../config.php'); // Assuming this has your database connection
include('admin_header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Payments</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .user-payments-container {
            height: 100vh;
            margin-top: 0;
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .title {
            color: #007bff;
        }

        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-input {
            padding: 8px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-button {
            padding: 8px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 8px;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .payments-table {
            width: 80%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin: 0 auto;
        }

        .payments-table th,
        .payments-table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .payments-table th {
            background-color: #007bff;
            color: white;
        }

        .no-results {
            text-align: center;
            color: #999;
            padding: 20px;
        }

       .payments-table th {
    font-size: 17px !important;
}

.payments-table td {
    font-size: 15px !important;
}

    th,
    tr{
        border: 1px solid #999;
    }


        tr{
            font-size: 16px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="user-payments-container">
        <center>
            <h2 class="title">User Payment Details</h2>

            <form class="search-form" onsubmit="return false;">
                <input
                    type="text"
                    placeholder="Search by user, car, plate, payment ID, status"
                    id="search-input"
                    class="search-input"
                />
                <button type="button" onclick="filterPayments()" class="search-button">Search</button>
            </form>

            <table class="payments-table" id="payments-table">
                <thead>
                    <tr>
                        <th>Full Name(Passanger)</th>
                        <th>Full Name(Driver)</th>
                        <th>Car Name</th>
                        <th>Number Plate</th>
                        <th>Payment Mode</th>
                    </tr>
                </thead>
                <tbody>
                            <tr>
                                <td>Keyur Dhaduk</td>
                                <td>Ronit Kakadiya</td>
                                <td>Hyundai</td>
                                <td>ABK-JKN</td>
                                <td>Online</td>  <!-- Assuming payment mode is static; adjust if dynamic -->
                            </tr>
                </tbody>
            </table>
        </center>
    </div>

    <script>
        function filterPayments() {
            var input, filter, table, tr, td, i, j;
            input = document.getElementById("search-input");
            filter = input.value.toLowerCase();
            table = document.getElementById("payments-table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Start from 1 to skip header
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
