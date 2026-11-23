<?php
  include('database.php');
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f0f2f5; /* optional, just to make it look nicer */
      margin: 0;
    }
    .reg-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 300px;
      font-size: 20px;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .reg-container input[type="text"],
    .reg-container input[type="password"] ,
    .reg-container input[type="email"],
    .reg-container input[type="tel"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .reg-container input[type="submit"] {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .reg-container input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .reg-container input[type="submit"]:active {
      background-color: #003d99;
      transform: scale(0.97);
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
    }

  </style>
</head>
<body>
  <div class="reg-container">
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="Post">
        <h2>Welocme On Board!</h2>
        Uername: <br>
        <input type="text" name="username"><br>
        Password: <br>
        <input type="password" name="password"><br>
        Email: <br>
        <input type="email" name="email"><br>
        Phone no: <br>
        <input type="tel" name="phone" placeholder="e.g. 123-456-7890"><br>
        <input type="submit" value="register">  
     </form>
</div>
</body>
</html>
<?php
    $register=False;
    if($_SERVER["REQUEST_METHOD"] == "POST"){

      $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
      $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);

      if (empty($username)){
        echo"Please enter a user name.";
      }
      elseif(empty($password)){
        echo"Please enter a password.";
      }
      elseif(empty($email)){
        echo"Please enter a email.";
      }
      elseif(empty($phone)){
        echo"Please enter a phone_no.";
      }
      else{
        $hash=password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO passenger (name, password, email, phone_no)
                VALUES ('$username', '$hash', '$email', '$phone')";
        mysqli_query($conn, $sql);
        $passenger_id = mysqli_insert_id($conn);
        $_SESSION['passenger_id'] = $passenger_id;

        $update_sql = "UPDATE passenger SET flight_history = 0 WHERE `passenger_id` = $passenger_id";
        mysqli_query($conn, $update_sql);

        echo"You are now registered!";
        $register=True;
      }
    }
    
    if (isset($conn)) {
      mysqli_close($conn);
    }
    if ($register){
    header("Location: flight.php");
    exit();
    }