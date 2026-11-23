<?php include("database.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>

    .form-container {
      border: solid;
      border-width: 1px;
      padding: 20px;
      width: 350px;
      margin-top: 10px;
      margin-bottom: 30px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
      
    }

    h2{
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      font-size: 29px;

    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      font-size: 15px;

    }

    .form-container input[type="text"], .form-container input[type="date"],.form-container input[type="number"] {
      font-family: 'Poppins', sans-serif;
      border: none;
      font-size: 15px;
      padding-left: 10px;
      padding-right: 10px;
      padding-top: 10px;
      padding-bottom: 10px;
      margin-bottom: 15px;
      cursor: text;

    }

    .form-container input[type="submit"] {
      font-family: 'Poppins', sans-serif;
      font-size: 15px;
      background-color:rgb(57, 87, 223);
      color: white;
      border: none;
      padding-left: 10px;
      padding-right: 10px;
      padding-top: 10px;
      padding-bottom: 10px;
      margin-bottom: 15px;
      cursor: pointer;
      transition: 0.3s;

    }

    .form-container input[type="submit"]:hover {
      background-color: rgb(33, 44, 199);
    }

    .form-container input[type="submit"]:active {
      background-color: rgb(33, 44, 199);
      transform: scale(0.97);
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
    }

  </style>
  </head>
<body>
  <div class="form-container">
    <h2>Book a Flight</h2>
    <form method="POST">
      <label>Departure Location:</label>
      <input type="text" name="departure" placeholder="Departure Location" required><br>
      <br>

      <label>Destination:</label>
      <input type="text" name="destination" placeholder="Destination" required><br>
      <br>

      <label>Departing Date:</label>
      <input type="date" name="d_time" required><br>
      <br>

      <label>Passengers:</label>
      <input type="number" name="passenger" min="1" value="1" required><br>
      <br>

      <label>Class:</label>
      <input type="text" name="class" placeholder="Economy Class" required><br>
      <br>

      <input type="submit" name="search" value="Search Flight">
    </form>
  </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
  $departure = $_POST['departure'];
  $destination = $_POST['destination'];
  // $class = $_POST['class'];
  $class = strtolower(trim($_POST['class']));
  $departing_date = $_POST['d_time'];
  $passengers = $_POST['passenger'];

  $sql = "SELECT flight_id, arrival_date, arrival_time, departure_date, departure_time, `time delay`, seat, `first class`, `business class`, `economy class`, `from`, `to` FROM flight WHERE departure_date = '$departing_date' AND `to` = '$destination' AND `from` = '$departure' AND `$class` >= '$passengers'";

  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr>
            <th>Flight ID</th>
            <th>Departure Date</th>
            <th>Departure Time</th>
            <th>Arrival Date</th>
            <th>Arrival Time</th>
            <th>From</th>
            <th>To</th>
            <th>First Class Seats</th>
            <th>Economy Class Seats</th>
            <th>Business Class Seats</th>
            <th>Total Seats</th>
            <th>Action</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      $total_seats = $row['first class'] + $row['business class'] + $row['economy class'];
      echo "<tr>";
      echo "<td>{$row['flight_id']}</td>";
      echo "<td>{$row['departure_date']}</td>";
      echo "<td>{$row['departure_time']}</td>";
      echo "<td>{$row['arrival_date']}</td>";
      echo "<td>{$row['arrival_time']}</td>";
      echo "<td>{$row['from']}</td>";
      echo "<td>{$row['to']}</td>";
      echo "<td>{$row['first class']}</td>";
      echo "<td>{$row['economy class']}</td>";
      echo "<td>{$row['business class']}</td>";
      echo "<td>$total_seats</td>";
      echo "<td><a href='payment.php?flight_id={$row['flight_id']}&class=" . urlencode($class) . "&passenger={$passengers}'>Proceed to Payment</a></td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "No flights found.";
  }
}
?>
