<?php
include("database.php");

// Check if flight_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flight_id'])) {
    $flight_id = $_POST['flight_id'];

    // Prepare and execute deletion
    $stmt = $conn->prepare("DELETE FROM flight WHERE flight_id = ?");
    $stmt->bind_param("i", $flight_id);

    if ($stmt->execute()) {
        // Redirect back to admin dashboard or list
        header("Location: admin_manage_flights.php?message=Flight+deleted+successfully");
        exit();
    } else {
        echo "Error deleting flight: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
