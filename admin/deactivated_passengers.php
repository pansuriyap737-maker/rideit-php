<?php
session_start();
include('../config.php'); // Assuming this has your database connection
include('admin_header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Deactivated Users</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .user-info-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .manages-user-title {
            color: black;
            margin-bottom: 20px;
            font-family: "Poppins", sans-serif;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .search-user {
            padding: 8px;
            width: 250px;
            height: 50px;
            border-radius: 10px;
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
            transition: 0.3s;
        }

        .search-user-btn:hover {
            transform: scale(105%);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 17px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 20px;
        }

        .no-users {
            text-align: center;
            color: #777;
            padding: 20px;
            font-size: 16px;
        }

        .activate {
            padding: 5px;
            font-size: 15px;
            border: 2px solid rgb(10, 174, 10);
            border-radius: 10px;
            background-color: white;
            color: rgb(10, 174, 10);
            transition: 0.3s;
        }

        .activate:hover {
            scale: 105%;
        }

        /* Notification styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }

        .notification.error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="user-info-container">
        <h2 class="manages-user-title">Deactivated Passengers</h2>

        <div class="top-bar">
            <form class="search-bar" onsubmit="return false;">
                <input
                    type="text"
                    name="search"
                    placeholder="Search deactivated users by name, email, or phone"
                    id="search-input"
                    class="search-user"
                />
                <button type="button" onclick="filterUsers()" class="search-user-btn">Search</button>
            </form>
        </div>

        <table id="users-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Deactivated Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                        <tr>
                            <td>Vatsal Sabhaya</td>
                            <td>mnb@gmail.com</td>
                            <td>9067435675</td>
                            <td>
                                <span>Deactivated</span>
                            </td>
                            <td>12/10/2025</td>
                            <td>
                                <button class="activate" onclick="reactivateUser('<?php echo $user['id']; ?>')">
                                    Reactivate
                                </button>
                            </td>
                        </tr>
            </tbody>
        </table>
    </div>

    <!-- Notification Div -->
    <div id="notification" class="notification"></div>

    <script>
        function filterUsers() {
            var input, filter, table, tr, td, i, j;
            input = document.getElementById("search-input");
            filter = input.value.toLowerCase();
            table = document.getElementById("users-table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j] && (j === 0 || j === 1 || j === 2)) {  // Username, Email, Phone
                        if (td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function reactivateUser(userId) {
            fetch(`/api/users/${userId}/status`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 'active' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('User has been reactivated successfully!', 'success');
                    location.reload();  // Reload to update the list
                } else {
                    showNotification(`Failed to reactivate user: ${data.message}`, 'error');
                }
            })
            .catch(error => {
                showNotification(`Error: ${error.message}`, 'error');
            });
        }

        function showNotification(message, type) {
            const notificationDiv = document.getElementById('notification');
            notificationDiv.textContent = message;
            notificationDiv.className = 'notification ' + (type === 'success' ? 'success' : 'error');
            notificationDiv.style.display = 'block';
            setTimeout(() => {
                notificationDiv.style.display = 'none';
            }, 4000);
        }
    </script>
</body>
</html>
