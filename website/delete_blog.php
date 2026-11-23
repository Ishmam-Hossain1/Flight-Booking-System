<?php
include 'database.php';
session_start();

// Check if passenger is logged in
if (!isset($_SESSION['passenger_id'])) {
    header("Location: login.php");
    exit;
}

$passenger_id = $_SESSION['passenger_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['blog_id'])) {
    $blog_id = $_POST['blog_id'];

    // Check if this passenger is the owner of the blog (via access table)
    $checkStmt = $conn->prepare("SELECT * FROM access WHERE blog_id = ? AND passenger_id = ?");
    $checkStmt->bind_param("ii", $blog_id, $passenger_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Blog belongs to this user, proceed with deletion
        $deleteStmt = $conn->prepare("DELETE FROM user_blog WHERE blog_id = ?");
        $deleteStmt->bind_param("i", $blog_id);
        $deleteStmt->execute();

        // Optional: delete from access table too
        $deleteAccess = $conn->prepare("DELETE FROM access WHERE blog_id = ?");
        $deleteAccess->bind_param("i", $blog_id);
        $deleteAccess->execute();

        // Optional: delete the image
        $imagePath = "uploads/blog_" . $blog_id . ".jpg";
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        header("Location: view_blog.php");
        exit;
    } else {
        echo "❌ You are not authorized to delete this blog.";
    }
}
?>
