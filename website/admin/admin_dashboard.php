<?php
include 'database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
        }
        .nav-card {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        .nav-card a {
            text-decoration: none;
            font-size: 18px;
            color: #2980b9;
        }
        .nav-card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <div class="nav-card">
        <a href="passengers.php">Manage Passengers</a>
    </div>

    <div class="nav-card">
        <a href="admin_manage_flights.php">Manage Flights</a>
    </div>

    <div class="nav-card">
        <a href="admin_manage_blogs.php">Manage Blogs</a>
    </div>

</body>
</html>



