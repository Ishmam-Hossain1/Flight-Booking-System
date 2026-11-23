<?php
include 'database.php';

// Fetch all blog posts from the database
$result = $conn->query("SELECT blog_id, post FROM user_blog");

echo "<h2>All Blog Posts</h2>";

while ($row = $result->fetch_assoc()) {
    $blog_id = $row['blog_id'];
    $fullPost = $row['post'];

    // Extract title only (first line as title, rest as content)
    $lines = explode("\n", $fullPost);
    $title = $lines[0];  // First line as title

    // Image path based on blog_id
    $imagePath = "uploads/blog_" . $blog_id . ".jpg";

    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";

    // Display the title
    echo "<h3>$title</h3>";

    // Show image if available
    if (file_exists($imagePath)) {
        echo "<img src='$imagePath' style='width:200px;'><br>";
    } else {
        echo "<i>No image available</i><br>";
    }

    // Correct "Read More" link with blog_id
    echo "<a href='view_blog_detail.php?blog_id=$blog_id'>Read More</a>";

    // ✅ Delete button form
    echo "<form method='POST' action='delete_blog.php' onsubmit=\"return confirm('Are you sure you want to delete this blog?');\">";
    echo "<input type='hidden' name='blog_id' value='$blog_id'>";
    echo "<button type='submit' style='color: red;'>Delete</button>";
    echo "</form>";

    echo "</div>";
}

$conn->close();
?>




