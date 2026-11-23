<?php
  // session_start();
  // if(!isset($_SESSION['passenger_id'])) {
  //     header("Location: login.php"); // Redirect to login page if not logged in
  //     exit();
  // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
</head>
<body>
  <h1>Welcome to Our Website!</h1>
  <p>Click the button below book a flight:</p>
  
  <!-- Payment Button -->
  <form action="flight.php" method="GET">
    <input type="submit" value="Book a Flight" />
  </form>
</body>
</html>
