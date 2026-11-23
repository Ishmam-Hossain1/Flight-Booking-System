<?php
include('database.php');
session_start();

if (!isset($_SESSION['passenger_id'])) {
    echo "User not logged in!";
    exit();
}

$passenger_id = $_SESSION['passenger_id'];

// Fetch Passenger Details
$passenger_query = "SELECT p.flight_class, p.seat_booked, b.flight_id
                    FROM passenger p 
                    JOIN books b ON p.passenger_id = b.passenger_id
                    WHERE p.passenger_id = '$passenger_id'";
$passenger_result = mysqli_query($conn, $passenger_query);

if (!$passenger_result || mysqli_num_rows($passenger_result) == 0) {
    echo "Passenger details not found!";
    exit();
}

$passenger = mysqli_fetch_assoc($passenger_result);
$current_class = $passenger['flight_class'];
$seats_booked = $passenger['seat_booked'];
$flight_id = $passenger['flight_id'];

if ($current_class == 'first class') {
    echo "You are already in the highest class! No upgrade possible.";
    exit();
}

// Fetch Flight Seat Availability
$flight_query = "SELECT `first class`, `business class`, `economy class` FROM flight WHERE flight_id = '$flight_id'";
$flight_result = mysqli_query($conn, $flight_query);

if (!$flight_result || mysqli_num_rows($flight_result) == 0) {
    echo "Flight not found!";
    exit();
}

$flight = mysqli_fetch_assoc($flight_result);

// Determine Upgrade Options
$can_upgrade_to = [];

if ($current_class == 'economy class') {
    if ($flight['business class'] >= $seats_booked) {
        $can_upgrade_to[] = 'business class';
    }
    if ($flight['first class'] >= $seats_booked) {
        $can_upgrade_to[] = 'first class';
    }
} elseif ($current_class == 'business class') {
    if ($flight['first class'] >= $seats_booked) {
        $can_upgrade_to[] = 'first class';
    }
}

// Handle Upgrade Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upgrade_to'])) {
    $new_class = $_POST['upgrade_to'];
    // echo "Current Class: " . htmlspecialchars($current_class) . "<br>"; #business class
    // echo "Upgraded To: " . htmlspecialchars($new_class) . "<br>"; #first class
    // echo "$flight_id" . "<br>"; 
    // echo "$passenger_id";
    // exit();

    if (!in_array($new_class, $can_upgrade_to)) {
        echo "Invalid upgrade option!";
        exit();
    }

    // Check if enough seats are available in the new class
    if ($flight[$new_class] < $seats_booked) {
        echo "Not enough seats available in the upgraded class!";
        exit();
    }

    // Update Flight Seat Counts
    // Deduct seats from the current class and add them to the upgraded class
    #$update_flight_query = "
        #UPDATE flight SET `{$current_class}` = `{$current_class}` + $seats_booked,`{$new_class}` = `{$new_class}` - $seats_booked WHERE flight_id = '$flight_id'";
    $update_flight_query = "UPDATE flight f join books b on f.flight_id = b.flight_id join passenger p on p.passenger_id = b.passenger_id SET `$current_class` = `$current_class` + $seats_booked, `$new_class` = `$new_class` - $seats_booked WHERE p.passenger_id = '$passenger_id'";
    if (!mysqli_query($conn, $update_flight_query)) {
        echo "Failed to update flight seat counts!";
        exit();
    }

    // Update Passenger Class
    $update_passenger_query = "
        UPDATE passenger 
        SET flight_class = '$new_class'
        WHERE passenger_id = '$passenger_id'
    ";
    if (!mysqli_query($conn, $update_passenger_query)) {
        echo "Failed to update passenger flight class!";
        exit();
    }

    // Redirect to profile after upgrade
    header("Location: user_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upgrade Seat</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f8f9fa;
    }
    h2 {
      color: #343a40;
    }
    .upgrade-options {
      margin-top: 20px;
    }
    .upgrade-options button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      margin: 5px;
      border-radius: 5px;
      cursor: pointer;
    }
    .upgrade-options button:hover {
      background-color: #0056b3;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>Upgrade Seat</h2>

  <?php if (empty($can_upgrade_to)): ?>
    <p>No upgrade options available for your flight.</p>
  <?php else: ?>
    <form method="POST" class="upgrade-options">
      <p>You can upgrade your seat to:</p>
      <?php foreach ($can_upgrade_to as $option): ?>
        <button type="submit" name="upgrade_to" value="<?php echo htmlspecialchars($option); ?>">
          Upgrade to <?php echo ucfirst($option); ?>
        </button>
      <?php endforeach; ?>
    </form>
  <?php endif; ?>

  <a href="user_profile.php">Back to Profile</a>

</body>
</html>
