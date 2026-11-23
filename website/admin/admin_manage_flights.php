<?php
include("database.php");

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_flight'])) {
    $flight_id = $_POST['flight_id'];
    $conn->query("DELETE FROM flight WHERE flight_id = '$flight_id'");
}

// Fetch all flights
$result = $conn->query("SELECT * FROM flight");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Flights</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      padding: 20px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background-color: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #004aad;
      color: white;
    }

    form {
      display: inline;
    }

    .form-section {
      margin-bottom: 30px;
      background-color: white;
      padding: 20px;
      width: 100%;
      max-width: 900px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .form-section input[type="text"],
    .form-section input[type="date"],
    .form-section input[type="time"],
    .form-section input[type="number"] {
      padding: 8px;
      margin: 5px;
      width: 160px;
    }

    .form-section button {
      padding: 8px 15px;
      background-color: #004aad;
      color: white;
      border: none;
      cursor: pointer;
    }

    .form-section button:hover {
      background-color: #003080;
    }
  </style>
</head>
<body>

  <h2>Add New Flight</h2>
  <div class="form-section">
    <form method="POST" action="add_flight.php">
      <input type="text" name="from" placeholder="From" required>
      <input type="text" name="to" placeholder="To" required>
      <input type="date" name="departure_date" required>
      <input type="time" name="departure_time" required>
      <input type="date" name="arrival_date" required>
      <input type="time" name="arrival_time" required>
      <input type="text" name="availability" placeholder="Available/Cancelled" required>
      <input type="text" name="time_delay" placeholder="Delay (HH:MM:SS)">
      <input type="number" name="seat" placeholder="Total Seats" required>
      <input type="number" name="upgrade_seat" placeholder="Upgradable Seats">
      <input type="number" name="first_class" placeholder="First Class">
      <input type="number" name="business_class" placeholder="Business Class">
      <input type="number" name="economy_class" placeholder="Economy Class">
      <input type="number" name="admin_id" placeholder="Admin ID" required>
      <button type="submit">Add Flight</button>
    </form>
  </div>

  <h2>All Flights</h2>
  <table>
    <tr>
      <th>ID</th><th>From</th><th>To</th><th>Dep Date</th><th>Dep Time</th>
      <th>Arr Date</th><th>Arr Time</th><th>Delay</th><th>Seats</th>
      <th>F</th><th>B</th><th>E</th><th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['flight_id'] ?></td>
        <td><?= $row['from'] ?></td>
        <td><?= $row['to'] ?></td>
        <td><?= $row['departure_date'] ?></td>
        <td><?= $row['departure_time'] ?></td>
        <td><?= $row['arrival_date'] ?></td>
        <td><?= $row['arrival_time'] ?></td>
        <td><?= $row['time_delay'] ?></td>
        <td><?= $row['seat'] ?></td>
        <td><?= $row['first class'] ?></td>
        <td><?= $row['business class'] ?></td>
        <td><?= $row['economy class'] ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="flight_id" value="<?= $row['flight_id'] ?>">
            <button type="submit" name="delete_flight" onclick="return confirm('Delete this flight?')">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>

