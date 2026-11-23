<?php include("database.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Flight Booking</title>
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
    h2 {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      font-size: 29px;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      font-size: 15px;
    }
    .form-container input[type="text"], 
    .form-container input[type="date"],
    .form-container input[type="number"] {
      font-family: 'Poppins', sans-serif;
      border: none;
      font-size: 15px;
      padding: 10px;
      margin-bottom: 15px;
      cursor: text;
    }
    .form-container input[type="submit"] {
      font-family: 'Poppins', sans-serif;
      font-size: 15px;
      background-color: rgb(57, 87, 223);
      color: white;
      border: none;
      padding: 10px;
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
    .my-table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      font-family: 'Poppins', sans-serif;
      font-size: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      overflow: hidden;
    }
    .my-table th, .my-table td {
      border: 1px solid #ddd;
      padding: 16px 20px;
      text-align: center;
    }
    .my-table th {
      background-color: rgb(57, 87, 223);
      color: white;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .my-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .my-table tr:hover {
      background-color: #e6f0ff;
      transition: background-color 0.3s;
    }
  </style>
</head>

<body>

<div class="form-container">
  <h2>Book a Flight</h2>
  <form method="POST">
    <label>Departure Location:</label>
    <input type="text" name="departure" required><br>

    <label>Destination:</label>
    <input type="text" name="destination" required><br>

    <label>Departing Date:</label>
    <input type="date" name="d_time" required><br>

    <label>Return Date (Optional):</label>
    <input type="date" name="return_date"><br>

    <label>Passengers:</label>
    <input type="number" name="passenger" min="1" value="1" required><br>

    <label>Class:</label>
    <input type="text" name="class" placeholder="Economy Class" required><br>

    <input type="submit" name="search" value="Search Flight">
  </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
  $departure = $_POST['departure'];
  $destination = $_POST['destination'];
  $class = strtolower(trim($_POST['class']));
  $departing_date = $_POST['d_time'];
  $passengers = $_POST['passenger'];
  $return_date = !empty($_POST['return_date']) ? $_POST['return_date'] : null;

  $sql = "SELECT * FROM flight
          WHERE departure_date = '$departing_date'
          AND `from` = '$departure'
          AND `to` = '$destination'
          AND `$class` >= '$passengers'";
  $result = mysqli_query($conn, $sql);

  $result_return = null;
  if ($return_date) {
    $sql_return = "SELECT * FROM flight
                   WHERE departure_date = '$return_date'
                   AND `from` = '$destination'
                   AND `to` = '$departure'
                   AND `$class` >= '$passengers'";
    $result_return = mysqli_query($conn, $sql_return);
  }

  if (($result && mysqli_num_rows($result) > 0) || ($result_return && mysqli_num_rows($result_return) > 0)) {
    echo '<form method="POST" action="payment.php">';

    // Hidden fields for class and passenger
    echo '<input type="hidden" name="class" value="' . htmlspecialchars($class) . '">';
    echo '<input type="hidden" name="passenger" value="' . htmlspecialchars($passengers) . '">';

    // Departure Flights Table
    if ($result && mysqli_num_rows($result) > 0) {
      echo "<h2 style='text-align:center;'>Departure Flights</h2>";
      echo "<table class='my-table'>";
      echo "<tr>
              <th>Select</th>
              <th>Flight ID</th>
              <th>Departure Date</th>
              <th>Departure Time</th>
              <th>Arrival Date</th>
              <th>Arrival Time</th>
              <th>Duration</th>
              <th>From</th>
              <th>To</th>
              <th>First Class Seats</th>
              <th>Economy Class Seats</th>
              <th>Business Class Seats</th>
              <th>Total Seats</th>
            </tr>";

      while ($row = mysqli_fetch_assoc($result)) {
        $total_seats = $row['first class'] + $row['business class'] + $row['economy class'];
        $duration = (new DateTime($row['departure_time']))->diff(new DateTime($row['arrival_time']))->format('%H:%I');

        echo "<tr>";
        echo "<td>
        <input type='radio' name='selected_flight' 
          value='flight_id={$row['flight_id']}&class=" . urlencode($class) . "&passenger=" . urlencode($passengers) . "' required>
        </td>";
      
        echo "<td>{$row['flight_id']}</td>";
        echo "<td>{$row['departure_date']}</td>";
        echo "<td>{$row['departure_time']}</td>";
        echo "<td>{$row['arrival_date']}</td>";
        echo "<td>{$row['arrival_time']}</td>";
        echo "<td>$duration</td>";
        echo "<td>{$row['from']}</td>";
        echo "<td>{$row['to']}</td>";
        echo "<td>{$row['first class']}</td>";
        echo "<td>{$row['economy class']}</td>";
        echo "<td>{$row['business class']}</td>";
        echo "<td>$total_seats</td>";
        echo "</tr>";
      }
      echo "</table>";
    }

    // Return Flights Table (Optional)
    if ($result_return && mysqli_num_rows($result_return) > 0) {
      echo "<h2 style='text-align:center;'>Return Flights (Optional)</h2>";
      echo "<table class='my-table'>";
      echo "<tr>
              <th>Select</th>
              <th>Flight ID</th>
              <th>Departure Date</th>
              <th>Departure Time</th>
              <th>Arrival Date</th>
              <th>Arrival Time</th>
              <th>Duration</th>
              <th>From</th>
              <th>To</th>
              <th>First Class Seats</th>
              <th>Economy Class Seats</th>
              <th>Business Class Seats</th>
              <th>Total Seats</th>
            </tr>";

      while ($row = mysqli_fetch_assoc($result_return)) {
        $total_seats = $row['first class'] + $row['business class'] + $row['economy class'];
        $duration = (new DateTime($row['departure_time']))->diff(new DateTime($row['arrival_time']))->format('%H:%I');

        echo "<tr>";
        echo "<td>
                <input type='checkbox' name='return_flight' value='{$row['flight_id']}'>
              </td>";
        echo "<td>{$row['flight_id']}</td>";
        echo "<td>{$row['departure_date']}</td>";
        echo "<td>{$row['departure_time']}</td>";
        echo "<td>{$row['arrival_date']}</td>";
        echo "<td>{$row['arrival_time']}</td>";
        echo "<td>$duration</td>";
        echo "<td>{$row['from']}</td>";
        echo "<td>{$row['to']}</td>";
        echo "<td>{$row['first class']}</td>";
        echo "<td>{$row['economy class']}</td>";
        echo "<td>{$row['business class']}</td>";
        echo "<td>$total_seats</td>";
        echo "</tr>";
      }
      echo "</table>";
    }

    echo '<div style="text-align:center;">
            <input type="submit" name="proceed" value="Proceed to Payment" style="padding: 10px 20px; margin-top: 20px;">
          </div>';

    echo '</form>';
  } else {
    echo "<p style='text-align:center;'>No flights found.</p>";
  }
}
?>

</body>
</html>
