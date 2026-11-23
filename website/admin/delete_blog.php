<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blog_id'])) {
    $blog_id = intval($_POST['blog_id']);

    // Delete image if exists
    $imagePath = "uploads/blog_" . $blog_id . ".jpg";
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM user_blog WHERE blog_id = ?");
    $stmt->bind_param("i", $blog_id);

    if ($stmt->execute()) {
        header("Location: admin_manage_blogs.php?deleted=1");
        exit;
    } else {
        echo "Failed to delete: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>






