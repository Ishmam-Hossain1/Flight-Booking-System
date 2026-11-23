<?php
include 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blog_id = intval($_POST['blog_id']);
    $passenger_id = $_SESSION['passenger_id'];
    $new_comment = trim($_POST['new_comment']);

    if (!empty($new_comment)) {
        // Fetch existing comments
        $query = "SELECT comment FROM user_blog WHERE blog_id = $blog_id";
        $result = $conn->query($query);
        $existing_comment = "";

        if ($row = $result->fetch_assoc()) {
            $existing_comment = $row['comment'];
        }

        // Append new comment
        $formatted_comment = "$passenger_id: $new_comment";
        $updated_comment = $existing_comment . "\n" . $formatted_comment;

        // Update database
        $stmt = $conn->prepare("UPDATE user_blog SET comment = ? WHERE blog_id = ?");
        $stmt->bind_param("si", $updated_comment, $blog_id);
        $stmt->execute();
    }
}

header("Location: view_blog_detail.php?blog_id=$blog_id");
exit;
?>


