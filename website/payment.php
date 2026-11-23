<?php
include('database.php');
session_start(); // Start the session to access passenger_id

if (!isset($_SESSION['passenger_id'])) {
    echo "User not logged in!";
    exit();
}

$passenger_id = $_SESSION['passenger_id'];

$tag = 'none';
$discount = 0;
$luggage_selected = false;
$luggage_option = "";

// Handle POST (Form submissions)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // When luggage option is selected
    if (isset($_POST['select_luggage'])) {
        $luggage_selected = true;
        $luggage_option = $_POST['luggage_option'];
        $flight_id = $_POST['flight_id'];
        $class = strtolower(trim($_POST['class']));
        $passengers = $_POST['passenger'];

    // When final payment is submitted
    } elseif (isset($_POST['submit'])) {
        $flight_id = $_POST['flight_id'];
        $class = strtolower(trim($_POST['class']));
        $passengers = $_POST['passenger'];
        $luggage_option = $_POST['luggage_option'];

        if (isset($_POST['payment_method'])) {
            $payment_method = $_POST['payment_method'];

            $credit_card = $mobile_banking = $bank_payment = 0;
            if ($payment_method == 'credit_card') $credit_card = 1;
            elseif ($payment_method == 'mobile_banking') $mobile_banking = 1;
            elseif ($payment_method == 'bank_payment') $bank_payment = 1;

            $payment = "INSERT INTO payment (credit_card, mobile_banking, bank_payment)
                        VALUES ('$credit_card', '$mobile_banking', '$bank_payment')";

            if (mysqli_query($conn, $payment)) {
                $payment_id = mysqli_insert_id($conn);
                $makes = "INSERT INTO makes (passenger_id, payment_id)
                          VALUES ('$passenger_id', '$payment_id')";
                mysqli_query($conn, $makes);

                // Update seat count
                if ($class == 'economy class') {
                    $update_flight_seats = "UPDATE flight SET `economy class` = `economy class` - $passengers WHERE flight_id = '$flight_id'";
                } elseif ($class == 'business class') {
                    $update_flight_seats = "UPDATE flight SET `business class` = `business class` - $passengers WHERE flight_id = '$flight_id'";
                } elseif ($class == 'first class') {
                    $update_flight_seats = "UPDATE flight SET `first class` = `first class` - $passengers WHERE flight_id = '$flight_id'";
                }

                $seat_booked = "UPDATE passenger SET `seat_booked` = $passengers WHERE passenger_id = '$passenger_id'";
                mysqli_query($conn, $seat_booked);


                if (mysqli_query($conn, $update_flight_seats)) {
                    $update_history = "UPDATE passenger SET flight_history = flight_history + 1 WHERE passenger_id = '$passenger_id'";
                    $set_passenger_class = "UPDATE passenger SET flight_class = '$class' WHERE passenger_id = '$passenger_id'";
                    mysqli_query($conn, $update_history);
                    mysqli_query($conn, $set_passenger_class);
                    $total_seat = "UPDATE flight SET `seat` = `economy class` + `business class` + `first class` WHERE flight_id = '$flight_id'";
                    mysqli_query($conn, $total_seat);

                    $books =  "INSERT INTO books (flight_id, passenger_id) VALUES ('$flight_id', '$passenger_id')";

                    mysqli_query($conn, $books);

                    $notify = "UPDATE passenger SET notification = 'Your Flight is Booked!' WHERE passenger_id = '$passenger_id'";
                    mysqli_query($conn, $notify);

                    echo "<p>Booking complete! Payment processed and seat count updated.</p>";
                    mysqli_close($conn);
                    exit();
                }

  
            }
        } else {
            echo "<p style='color:red;'>Please select a payment method before submitting!</p>";
        }
    }
}

// Handle GET (initial page load)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $passengers = isset($_GET['passenger']) ? $_GET['passenger'] : null;
    $flight_id = isset($_GET['flight_id']) ? $_GET['flight_id'] : null;
    $class = isset($_GET['class']) ? strtolower(trim($_GET['class'])) : null;
}

// Fetch flight price
$price = 0;
if (!empty($flight_id)) {
    $flight_query = "SELECT `economy_ticket_price`, `business_ticket_price`, `first_ticket_price` FROM flight WHERE flight_id = '$flight_id'";
    $flight_result = mysqli_query($conn, $flight_query);

    if ($flight_result && mysqli_num_rows($flight_result) > 0) {
        $row = mysqli_fetch_assoc($flight_result);

        if ($class == 'economy class') {
            $price = $row['economy_ticket_price'];
        } elseif ($class == 'business class') {
            $price = $row['business_ticket_price'];
        } elseif ($class == 'first class') {
            $price = $row['first_ticket_price'];
        }
    }
}

// Fetch flight history and set discount
$no_of_flights = 0;
$query = "SELECT flight_history FROM passenger WHERE passenger_id = '$passenger_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $no_of_flights = $row['flight_history'];

    if ($no_of_flights >= 10 && $no_of_flights < 20) {
        $tag = 'bronze';
        $discount = 10;
        $query = "UPDATE passenger SET bronze = 1, silver = 0, gold = 0 WHERE passenger_id = '$passenger_id'";
        mysqli_query($conn, $query);
    } elseif ($no_of_flights >= 20 && $no_of_flights < 30) {
        $tag = 'silver';
        $discount = 15;
        $query = "UPDATE passenger SET bronze = 0, silver = 1, gold = 0 WHERE passenger_id = '$passenger_id'";
        mysqli_query($conn, $query);
    } elseif ($no_of_flights >= 30) {
        $tag = 'gold';
        $discount = 20;
        $query = "UPDATE passenger SET bronze = 0, silver = 0, gold = 1 WHERE passenger_id = '$passenger_id'";
        mysqli_query($conn, $query);
    }
}

$query = "SELECT name, email, phone_no, seat_booked FROM passenger WHERE passenger_id = '$passenger_id'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $phone_no = $row['phone_no'];

}

// Calculate final ticket price if luggage selected
if ($luggage_selected || isset($_POST['submit'])) {
    if ($luggage_option == 'convenience') {
        $final_ticket_price = $price;
    } elseif ($luggage_option == 'comfort') {
        $final_ticket_price = $price + 2000;
    }
    $final_ticket_price = $final_ticket_price * (1 - $discount / 100);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Page</title>
  <style>
    body { 
        font-family: 'Poppins', sans-serif; 
        background-color: #f0f4f8; 
        font-size: 15px; 
    }
    .main-container { 
        display: flex; 
        justify-content: center;
        align-items: flex-start;
        gap: 30px;
        margin-top: 30px;
     }
    .payment-container, .flight-history-container, .luggage-container {
      border: solid 1px;
      padding: 20px;
      width: 350px;
      margin-top: 10px;
      margin-bottom: 30px;
      margin: 30px auto; 
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    }
    .payment-container input[type="submit"] {
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
    .payment-container input[type="submit"]:hover { 
      background-color: rgb(33, 44, 199); 
    }

    .payment-container input[type="submit"]:active {
      background-color: rgb(33, 44, 199);
      transform: scale(0.97);
      box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    .luggage-container input[type="submit"] {
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

    .luggage-container input[type="submit"]:hover { 
      background-color: rgb(33, 44, 199); 
    }

    .luggage-container input[type="submit"]:active {
      background-color: rgb(33, 44, 199);
      transform: scale(0.97);
      box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

<div class="main-container">

    <div class="flight-history-container">
        <h3>Your Flight History</h3>
        <p>You have completed <?php echo $no_of_flights; ?> flight(s) with us!</p>
        <p>Your loyalty-tag is <?php echo $tag; ?></p>
        <p>You get <?php echo $discount; ?>% discount</p>
    </div>

    <div class="luggage-container">
        <form action="payment.php" method="POST">
            <h4><?php echo ucfirst($class); ?> Convenience</h4>
            <p>Ticket Price: <?php echo $price; ?> Taka</p>
            <p>Discounted Ticket Price: <?php echo $price * (1 - $discount/100); ?> Taka</p>
            <p>Checked baggage: 30 kg</p>
            <p>Hand baggage 1 pc: 7 kg </p>

            <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
            <input type="hidden" name="class" value="<?php echo $class; ?>">
            <input type="hidden" name="passenger" value="<?php echo $passengers; ?>">
            <input type="hidden" name="luggage_option" value="convenience">

            <input type="submit" name="select_luggage" value="Select">
        </form>
    </div>

    <div class="luggage-container">
        <form action="payment.php" method="POST">
            <h4><?php echo ucfirst($class); ?> Comfort</h4>
            <p>Ticket Price: <?php echo $price + 2000; ?> Taka</p>
            <p>Discounted Ticket Price: <?php echo ($price + 2000) * (1 - $discount/100); ?> Taka</p>
            <p>Checked baggage: 35 kg</p>
            <p>Hand baggage 1 pc: 10 kg </p>

            <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
            <input type="hidden" name="class" value="<?php echo $class; ?>">
            <input type="hidden" name="passenger" value="<?php echo $passengers; ?>">
            <input type="hidden" name="luggage_option" value="comfort">

            <input type="submit" name="select_luggage" value="Select">
        </form>
    </div>

</div>
<div>
    <?php if ($luggage_selected): ?>
    <div class="payment-container">
        <h2>Complete Your Payment</h2>
        <p>You have selected <?php echo $class; ?> Flight with <?php echo ucfirst($luggage_option); ?> luggage.</p>
        <p>USER INFORMATION</p>
        <p>Name: <?php echo $name ?></p>
        <p>Email: <?php echo $email ?></p>
        <p>Phone_no: <?php echo $phone_no ?></p>
        <p>PAYMENT SUMMARY</p>
        <p><b>Final Ticket Price: <?php echo $final_ticket_price; ?> Taka</b></p>


        <form action="payment.php" method="POST">
            <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
            <input type="hidden" name="class" value="<?php echo $class; ?>">
            <input type="hidden" name="passenger" value="<?php echo $passengers; ?>">
            <input type="hidden" name="luggage_option" value="<?php echo $luggage_option; ?>">

            <p>Select Payment Method:</p>
            <input type="radio" id="credit_card" name="payment_method" value="credit_card"><label> Credit Card</label><br>
            <input type="radio" id="mobile_banking" name="payment_method" value="mobile_banking"><label> Mobile Banking</label><br>
            <input type="radio" id="bank_payment" name="payment_method" value="bank_payment"><label> Bank Payment</label><br><br>

            <input type="submit" name="submit" value="Done">
        </form>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
