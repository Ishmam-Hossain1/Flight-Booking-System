<?php
include 'database.php';

// Fetch all blog posts
$result = $conn->query("SELECT blog_id, post, comment, `q/a`, review, admin_id FROM user_blog");

if (!$result || $result->num_rows === 0) {
    echo "<p>No blog posts found.</p>";
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Blogs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .blog-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .blog-container img {
            width: 200px;
            margin-top: 10px;
        }
        .delete-button {
            background-color: #e74c3c;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>Admin - Manage Blog Posts</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <?php
        $blog_id = $row['blog_id'];
        $post = $row['post'];
        $comment = $row['comment'];
        $qa = $row['q/a'];
        $review = $row['review'];
        $admin_id = $row['admin_id'];

        $title = explode("\n", $post)[0];
        $imagePath = "uploads/blog_" . $blog_id . ".jpg";
    ?>

    <div class="blog-container">
        <h3><?= htmlspecialchars($title) ?></h3>
        <!-- <?php if (file_exists($imagePath)): ?>
            <img src="<?= $imagePath ?>" alt="Blog Image">
        <?php else: ?>
            <i>No image available</i>
        <?php endif; ?> -->

        <p><strong>Comment:</strong> <?= htmlspecialchars($comment) ?></p>
        <!-- <p><strong>Q/A:</strong> <?= htmlspecialchars($qa) ?></p>
        <p><strong>Review:</strong> <?= htmlspecialchars($review) ?></p> -->
        <p><strong>Admin ID:</strong> <?= htmlspecialchars($admin_id) ?></p>

        <!-- <a href="view_blog_detail.php?blog_id=<?= $blog_id ?>">Read More</a> -->

        <form method="POST" action="delete_blog.php" onsubmit="return confirm('Delete this blog post?');">
            <input type="hidden" name="blog_id" value="<?= $blog_id ?>">
            <button type="submit" class="delete-button">Delete Blog</button>
        </form>
    </div>
<?php endwhile; ?>

<?php $conn->close(); ?>

</body>
</html>



