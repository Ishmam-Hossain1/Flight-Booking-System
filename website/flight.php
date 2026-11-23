<?php
session_start(); // Start the session
include("database.php");
?>


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

.book-button {
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  background-color: rgb(57, 87, 223);
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  transition: 0.3s;
  border-radius: 5px;
}

.book-button:hover {
  background-color: rgb(33, 44, 199);
}

.user-profile {
  position: absolute;
  top: 20px;
  right: 30px;
}

.notification {
  position: absolute;
  top: 20px;
  right: 155px;
}

.view_blog {
  position: absolute;
  top: 20px;
  right: 275px;
}

.add_blog {
  position: absolute;
  top: 20px;
  right: 385px;
}

.add_blog input[type="submit"] {
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  background-color: rgb(57, 87, 223);
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  transition: 0.3s;
  border-radius: 5px;
}

.add_blog input[type="submit"]:hover {
  background-color: rgb(33, 44, 199);
}

.view_blog input[type="submit"] {
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  background-color: rgb(57, 87, 223);
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  transition: 0.3s;
  border-radius: 5px;
}

.view_blog input[type="submit"]:hover {
  background-color: rgb(33, 44, 199);
}

.user-profile input[type="submit"] {
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  background-color: rgb(57, 87, 223);
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  transition: 0.3s;
  border-radius: 5px;
}

.user-profile input[type="submit"]:hover {
  background-color: rgb(33, 44, 199);
}

.notification input[type="submit"] {
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  background-color: rgb(57, 87, 223);
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  transition: 0.3s;
  border-radius: 5px;
}

.notification input[type="submit"]:hover {
  background-color: rgb(33, 44, 199);
}

.notification-box {
  background-color: #e6f0ff;
  border-left: 5px solid #3366cc;
  padding: 20px;
  margin: 20px auto;
  width: 80%;
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.notification-box h3 {
  margin-top: 0;
  color: #003399;
}

  </style>
  </head>
<body>
  <div class="main-container">
    <div class="form-container">
      <h2>Book a Flight</h2>
      <form method="POST">
        <label>Departure Location:</label>
        <input type="text" name="departure" placeholder="Departure Location" list="departure_list" required><br>
          <datalist id="departure_list">
          <?php
            $departure_query = "SELECT DISTINCT `from` AS departure FROM flight";
            $departure_result = mysqli_query($conn, $departure_query);
            if ($departure_result && mysqli_num_rows($departure_result) > 0) {
                while ($row = mysqli_fetch_assoc($departure_result)) {
                    echo '<option value="'.htmlspecialchars($row['departure']).'">';
                }
            } else {
                echo "No departure locations found.";
            }
          ?>

          </datalist>
        <br>


        <label>Destination:</label>
        <input type="text" name="destination" placeholder="Destination" required list="destination_list"><br>
        <datalist id="destination_list">
          <?php
            $destination_query = "SELECT DISTINCT `to` AS destination FROM flight";
            $destination_result = mysqli_query($conn, $destination_query);
            if ($destination_result && mysqli_num_rows($destination_result) > 0) {
                while ($row = mysqli_fetch_assoc($destination_result)) {
                    echo '<option value="'.htmlspecialchars($row['destination']).'">';
                }
            } else {
                echo '<option value="">No destination locations found.</option>';
            }
          ?>
        </datalist>
        <br>

        <label>Departing Date:</label>
        <input type="date" name="d_time" required><br>
        <br>

        <label>Passengers:</label>
        <input type="number" name="passenger" min="1" value="1" required><br>
        <br>

        <label>Class:</label>
        <input type="text" name="class" placeholder="Economy Class" required list="class_list"><br>
          <datalist id="class_list">
          <option value="First Class">
          <option value="Business Class">
          <option value="Economy Class">
          </datalist>
        <br>

        <input type="submit" name="search" value="Search Flight">

    </div>  
  </div>  
      </form>
    </div class = "top-buttons">
      <div class="user-profile">
        <form action="user_profile.php" method="POST">
          <input type="submit" name="user" value="user profile">
        </form>
      </div>
      <div class="notification">
        <form action="flight.php" method="POST">
          <input type="submit" name="notification" value="notification">
        </form>
      </div>

      <div class="view_blog">
        <form action="view_blog.php" method="POST">
          <input type="submit" name="view_blog" value="view blog">
        </form>
      </div>

      <div class="add_blog">
        <form action="add_blog.php" method="POST">
          <input type="submit" name="add_blog" value="add blog">
        </form>
      </div>

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

  $sql = "SELECT flight_id, arrival_date, arrival_time, departure_date, departure_time, `time_delay`, seat, `first class`, `business class`, `economy class`, `from`, `to` FROM flight WHERE departure_date = '$departing_date' AND `to` = '$destination' AND `from` = '$departure' AND `$class` >= '$passengers'";

  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    echo "<table class='my-table'>";
    echo "<tr>
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
            <th>Action</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      $total_seats = $row['first class'] + $row['business class'] + $row['economy class'];
      $duration = (new DateTime($row['departure_time']))->diff(new DateTime($row['arrival_time']))->format('%H:%I');

      echo "<tr>";
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
      echo "<td><a class ='book-button' href='payment.php?flight_id={$row['flight_id']}&class=" . urlencode($class) . "&passenger={$passengers}'>Book</a></td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "No flights found.";
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification'])) {
    // Check if session is active and passenger_id exists
    if (isset($_SESSION['passenger_id'])) {
        $passenger_id = $_SESSION['passenger_id'];

      $notif_query = "SELECT notification FROM passenger WHERE passenger_id = $passenger_id";
      $notif_result = mysqli_query($conn, $notif_query);

      if ($notif_result && mysqli_num_rows($notif_result) > 0) {
          $row = mysqli_fetch_assoc($notif_result);
          $notification = trim($row['notification']);
          
          if (!empty($notification)) {

              // Split the notifications into an array
              $notifications_array = explode("\n", $notification);

              // Clean, remove empty, remove duplicates, reverse
              $notifications_array = array_map('trim', $notifications_array); 
              $notifications_array = array_filter($notifications_array);
              $notifications_array = array_unique($notifications_array);
              $notifications_array = array_reverse($notifications_array);


              // Display notifications
              echo "<div class='notification-box'>";
              echo "<h3>Notifications</h3>";
              foreach ($notifications_array as $note) {
                  echo "<p>" . htmlspecialchars(trim($note)) . "</p><hr>";
              }
              echo "</div>";
          } else {
              echo "<div class='notification-box'><h3>No new notifications.</h3></div>";
          }
      } else {
          echo "Error: No results from the database query.";
      }

    } else {
        echo "Error: No session or passenger_id.";
    }
}
?>
