<?php
// Include database connection
include 'database.php';

// Start session to access passenger_id
session_start();
if (isset($_SESSION['passenger_id'])) {
    $passenger_id = $_SESSION['passenger_id'];
} else {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $fullPost = $title . "\n\n" . $content;

        $admin_id = 1; // Always set admin_id to 1

        // Insert into user_blog
        $stmt = $conn->prepare("INSERT INTO user_blog (post, admin_id) VALUES (?, ?)");
        $stmt->bind_param("si", $fullPost, $admin_id);
        $stmt->execute();

        $blog_id = $stmt->insert_id;

        // Record who posted it in access table
        $accessStmt = $conn->prepare("INSERT INTO access (passenger_id, blog_id) VALUES (?, ?)");
        $accessStmt->bind_param("ii", $passenger_id, $blog_id);
        $accessStmt->execute();

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $imagePath = "uploads/blog_" . $blog_id . ".jpg";
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        header("Location: view_blog.php");
        exit;
    }
}
?>

<!-- HTML form to add a blog post -->
<form action="add_blog.php" method="POST" enctype="multipart/form-data">
    <label>Blog Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Blog Content:</label><br>
    <textarea name="content" rows="5" cols="40" required></textarea><br><br>

    <label>Upload Image:</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <input type="submit" value="Post Blog">
</form>

