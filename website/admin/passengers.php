<?php
include('database.php');
session_start();

// ADD Passenger
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_passenger'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Note: should hash in real app
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);

    $query = "INSERT INTO passenger (name, password, email, phone_no, gold, silver, bronze, flight_history, `special Need`, notification, `user Authentication`, blog_id, Flight_class) 
              VALUES ('$name', '$password', '$email', '$phone_no', 0, 0, 0, 0, '', '', '', NULL, '')";
    mysqli_query($conn, $query);
}

// UPDATE Passenger
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_passenger'])) {
    $id = $_POST['id'];
    $fields = [
        'name', 'password', 'email', 'phone_no', 'gold', 'silver', 'bronze',
        'flight_history', 'special Need', 'notification', 'user Authentication','Flight_class'
    ];

    $update_fields = [];
    foreach ($fields as $field) {
        $escaped_value = mysqli_real_escape_string($conn, $_POST[str_replace(' ', '_', $field)]);
        $update_fields[] = "`$field` = '$escaped_value'";
    }

    $update_query = "UPDATE passenger SET " . implode(", ", $update_fields) . " WHERE passenger_id = '$id'";
    mysqli_query($conn, $update_query);
}

// DELETE Passenger
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_passenger'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM passenger WHERE passenger_id='$id'";
    mysqli_query($conn, $query);
}

// FETCH all passengers
$result = mysqli_query($conn, "SELECT * FROM passenger");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Passengers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            padding: 40px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            font-size: 14px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #3949ab;
            color: white;
        }

        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="number"] {
            padding: 6px;
            width: 150px;
            margin-right: 10px;
        }

        button {
            padding: 8px 12px;
            border: none;
            background: #3949ab;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #303f9f;
        }

        .delete-button {
            background: #d9534f;
        }

        .delete-button:hover {
            background: #c9302c;
        }

        .update-button {
            background: #5bc0de;
        }

        .update-button:hover {
            background: #31b0d5;
        }

        .scrollable-table {
            max-height: 600px;
            overflow: auto;
        }
    </style>
</head>
<body>

<h2>Add New Passenger</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone_no" placeholder="Phone No" required>
    <button type="submit" name="add_passenger">Add Passenger</button>
</form>

<h2>All Passengers</h2>
<div class="scrollable-table">
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Password</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Gold</th>
        <th>Silver</th>
        <th>Bronze</th>
        <th>Flight History</th>
        <th>Special Need</th>
        <th>Notification</th>
        <th>Authentication</th>
        <th>Blog ID</th>
        <th>Flight Class</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <form method="POST">
            <td><?php echo $row['passenger_id']; ?></td>
            <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
            <td><input type="text" name="password" value="<?php echo htmlspecialchars($row['password']); ?>"></td>
            <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></td>
            <td><input type="text" name="phone_no" value="<?php echo htmlspecialchars($row['phone_no']); ?>"></td>
            <td><input type="number" name="gold" value="<?php echo $row['gold'] ?? 0; ?>"></td>
            <td><input type="number" name="silver" value="<?php echo $row['silver'] ?? 0; ?>"></td>
            <td><input type="number" name="bronze" value="<?php echo $row['bronze'] ?? 0; ?>"></td>
            <td><input type="number" name="flight_history" value="<?php echo $row['flight_history'] ?? 0; ?>"></td>
            <td><input type="text" name="special_Need" value="<?php echo htmlspecialchars($row['special Need'] ?? ''); ?>"></td>
            <td><input type="text" name="notification" value="<?php echo htmlspecialchars($row['notification'] ?? ''); ?>"></td>
            <td><input type="text" name="user_Authentication" value="<?php echo htmlspecialchars($row['user Authentication'] ?? ''); ?>"></td>
            <td><input type="text" name="blog_id" value="<?php echo htmlspecialchars($row['blog_id'] ?? ''); ?>"></td>
            <td><input type="text" name="Flight_class" value="<?php echo htmlspecialchars($row['Flight_class'] ?? ''); ?>"></td>

            <td>
                <input type="hidden" name="id" value="<?php echo $row['passenger_id']; ?>">
                <button type="submit" name="update_passenger" class="update-button">Update</button>
                <button type="submit" name="delete_passenger" class="delete-button" onclick="return confirm('Are you sure?')">Delete</button>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</body>
</html>

