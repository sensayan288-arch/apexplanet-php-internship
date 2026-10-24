
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $message = 'Please fill in both fields.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO posts (title, content) VALUES (?, ?)'
        );

        $stmt->execute([$title, $content]);

        $message = 'Post created successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Post</title>
</head>
<body>
    <h1>Create a New Post</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="POST" action="create_post.php">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required maxlength="255">
        <br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="6" required></textarea>
        <br><br>

        <button type="submit">Create Post</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
