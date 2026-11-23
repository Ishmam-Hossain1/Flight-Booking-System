<?php
include('database.php');
session_start();

if (!isset($_SESSION['passenger_id'])) {
    echo "User not logged in!";
    exit();
}

$passenger_id = $_SESSION['passenger_id'];

// Handle Saving Profile Changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);

    $update_query = "UPDATE passenger SET email = '$new_email', phone_no = '$new_phone_no' WHERE passenger_id = '$passenger_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Failed to update profile!";
    }
}

// Handle Cancel Seat Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_seat'])) {
  $cancel_query = "UPDATE passenger 
                   SET seat_booked = 0, 
                       flight_history = CASE WHEN flight_history > 0 THEN flight_history - 1 ELSE 0 END
                   WHERE passenger_id = '$passenger_id'";
                   
  if (mysqli_query($conn, $cancel_query)) {
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } else {
      echo "Failed to cancel seat!";
  }
}


// Fetch User Details
$query = "SELECT name, email, phone_no, seat_booked FROM passenger WHERE passenger_id = '$passenger_id'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $phone_no = $row['phone_no'];
    $seat_booked = $row['seat_booked'];
}

// Check if Edit Mode
$edit_mode = isset($_POST['edit_profile']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .profile-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      width: 400px;
      text-align: center;
    }

    .profile-card h2 {
      margin-bottom: 20px;
      font-size: 28px;
      color: #3949ab;
    }

    .profile-details {
      text-align: left;
      margin-top: 20px;
    }

    .profile-details label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #3949ab;
    }

    .profile-details input[type="text"], .profile-details input[type="email"] {
      width: 100%;
      padding: 8px 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      background-color: #f5f5f5;
    }

    .profile-details input[readonly] {
      background-color: #f5f5f5;
    }

    .action-buttons {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
    }

    .action-buttons button, .action-buttons a {
      background-color: #3949ab;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 15px;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
    }

    .logout-button {
      background-color: #d9534f;
    }

    .cancel-button {
      background-color: #f0ad4e;
    }

    .action-buttons button:hover, .action-buttons a:hover {
      background-color: #303f9f;
    }

    .logout-button:hover {
      background-color: #c9302c;
    }

    .cancel-button:hover {
      background-color: #ec971f;
    }
  </style>
</head>
<body>

<div class="profile-card">
  <h2>User Profile</h2>

  <form method="POST" class="profile-details">
    <label>Name:</label>
    <input type="text" value="<?php echo htmlspecialchars($name); ?>" readonly>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" <?php echo $edit_mode ? '' : 'readonly'; ?>>

    <label>Phone No:</label>
    <input type="text" name="phone_no" value="<?php echo htmlspecialchars($phone_no); ?>" <?php echo $edit_mode ? '' : 'readonly'; ?>>

    <label>Seats Booked:</label>
    <input type="text" value="<?php echo htmlspecialchars($seat_booked); ?>" readonly>

    <div class="action-buttons">
      <?php if ($edit_mode): ?>
        <button type="submit" name="save_changes">Save</button>
      <?php else: ?>
        <button type="submit" name="edit_profile">Edit</button>
      <?php endif; ?>

      <?php if ($seat_booked > 0): ?>
        <button type="submit" name="cancel_seat" class="cancel-button">Cancel Seat</button>
      <?php endif; ?>

      <a href="logout.php" class="logout-button">Logout</a>
    </div>
  </form>
</div>

</body>
</html>
