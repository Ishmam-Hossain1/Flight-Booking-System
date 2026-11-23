<?php include("database.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 300px;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .login-container input[type="submit"] {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-container input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .login-container input[type="submit"]:active {
      background-color: #003d99;
      transform: scale(0.97);
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Login</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" value="Login">
    </form>
  </div>

</body>
</html>

<?php
include("database.php");
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
  $password = $_POST['password']; // raw password input

  if (!empty($username) && !empty($password)) {
    $query = "SELECT passenger_id, password FROM passenger WHERE name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
      mysqli_stmt_bind_result($stmt, $passenger_id, $hashed_password);
      mysqli_stmt_fetch($stmt);

      if (password_verify($password, $hashed_password)) {
        $_SESSION['passenger_id'] = $passenger_id;
        $seat_booked = "SELECT seat_booked from passenger where passenger_id = $passenger_id";
        $result = mysqli_query($conn , $seat_booked);
        $seat = mysqli_fetch_assoc($result);
        if ($seat && $seat['seat_booked'] > 0) {
        $delay_query = "SELECT f.flight_id, f.time_delay FROM books b JOIN flight f ON b.flight_id = f.flight_id join passenger p on p.passenger_id = b.passenger_id WHERE p.passenger_id = $passenger_id ORDER BY b.booking_time DESC LIMIT 1";
        $result = mysqli_query($conn , $delay_query);

        if ($result && mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $flight_id = $row['flight_id'];
          $flight_delay = $row['time_delay'];

            if ($flight_delay > "00:00:00") { 
            $message = "Your flight ID $flight_id is delayed by $flight_delay hours.";
            $safe_message = mysqli_real_escape_string($conn, $message);

            $update_query = "UPDATE passenger SET notification = CONCAT(IFNULL(notification, ''), '\n', '$safe_message') WHERE passenger_id = '$passenger_id'";
            $result = mysqli_query($conn , $update_query);

            $update_query = "UPDATE passenger SET notification = CONCAT(IFNULL(notification, ''), '\n', 'You will get 10% refund of your paid amaount') WHERE passenger_id = '$passenger_id'";
            $result = mysqli_query($conn , $update_query);

            }
        }
        }

        header("Location: flight.php"); // change this if needed
        exit();
      }
      else {
        $error = "Invalid password.";
        echo"invalid Password";
      }
    } else {
      $error = "User not found.";
      echo"User not found!";
    }

    mysqli_stmt_close($stmt);
  } else {
    $error = "Please enter both username and password.";
  }

  mysqli_close($conn);
}
?>