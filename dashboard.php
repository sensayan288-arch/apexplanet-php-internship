
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>!</h1>

    <p>You are logged in successfully.</p>
    <p><a href="posts.php">View All Posts</a></p>
<p><a href="create_post.php">Create a New Post</a></p>

    <a href="logout.php">Logout</a>
</body>
</html>
