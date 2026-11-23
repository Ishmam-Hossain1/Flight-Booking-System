<?php
include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO flight (`from`, `to`, departure_date, departure_time, arrival_date, arrival_time, availability, `time_delay`, seat, `upgrade seat`, `first class`, `business class`, `economy class`, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssiiiiiii",
        $_POST['from'], $_POST['to'], $_POST['departure_date'], $_POST['departure_time'],
        $_POST['arrival_date'], $_POST['arrival_time'], $_POST['availability'], $_POST['time_delay'],
        $_POST['seat'], $_POST['upgrade_seat'], $_POST['first_class'], $_POST['business_class'], $_POST['economy_class'], $_POST['admin_id']
    );

    if ($stmt->execute()) {
        header("Location: admin_manage_flights.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

