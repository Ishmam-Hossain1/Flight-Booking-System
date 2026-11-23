<?php include("database.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body{
      background-color: #f0f4f8;
    }


    .welcome-text {
      font-family: 'Poppins', sans-serif;
      text-align: center;
      margin-top: 150px;
      font-size: 50px;
    }

    .button-container {
      display: flex;
      justify-content: center; 
      gap: 40px; 
      margin-top: 80px; 
    }

    .Login-Button, .Register-button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .Login-Button:hover, .Register-button:hover {
      background-color: #0056b3;
    }

    .Login-Button:active, .Register-button:active {
      background-color: #003d99;
      transform: scale(0.97);
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <p class="welcome-text">Welcome to Our Airlines</p>

  <form method="POST">
    <div class="button-container">
      <button class="Login-Button" type="submit" name="login">Login</button>
      <button class="Register-button" type="submit" name="register">Register</button>
    </div>
  </form>
</body>
</html>

<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
      header("Location: register.php");
      exit();
    } elseif (isset($_POST['login'])) {
      header("Location: login.php");
      exit();
    }
  }
?>

