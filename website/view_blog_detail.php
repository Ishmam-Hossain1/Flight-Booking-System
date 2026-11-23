<?php
include 'database.php';
session_start();

if (!isset($_SESSION['passenger_id'])) {
    echo "You must be logged in to view or comment.";
    exit;
}

$passenger_id = $_SESSION['passenger_id'];

if (isset($_GET['blog_id'])) {
    $blog_id = intval($_GET['blog_id']);
    $result = $conn->query("SELECT * FROM user_blog WHERE blog_id = $blog_id");

    if ($row = $result->fetch_assoc()) {
        $fullPost = $row['post'];
        $comments = $row['comment'];

        // Split post into title and content
        $lines = explode("\n", $fullPost, 2);
        $title = $lines[0];
        $content = isset($lines[1]) ? nl2br(trim($lines[1])) : '';

        echo "<h2><strong>$title</strong></h2>";
        echo "<p>$content</p>";

        // Blog image
        $imagePath = "uploads/blog_" . $blog_id . ".jpg";
        if (file_exists($imagePath)) {
            echo "<img src='$imagePath' alt='Blog Image' style='width:300px;'><br><br>";
        } else {
            echo "<i>No image available</i><br><br>";
        }

        // Display comments
        echo "<h3>Comments:</h3>";
        if (!empty($comments)) {
            $commentLines = explode("\n", $comments);
            foreach ($commentLines as $cmt) {
                echo "<div style='border:1px solid #ccc; padding:8px; margin-bottom:6px;'>"
                   . htmlspecialchars($cmt) . "</div>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }

        // Comment form
        echo "
        <h4>Add a Comment:</h4>
        <form method='POST' action='add_comment.php'>
            <input type='hidden' name='blog_id' value='$blog_id'>
            <input type='hidden' name='passenger_id' value='$passenger_id'>
            <label>Your Comment:</label><br>
            <textarea name='new_comment' rows='4' cols='50' required></textarea><br><br>
            <input type='submit' value='Post Comment'>
        </form>
        ";
    } else {
        echo "Blog not found.";
    }
} else {
    echo "No blog selected.";
}

$conn->close();
?>


