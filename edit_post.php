
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die('Invalid post ID.');
}

$stmt = $pdo->prepare(
    'SELECT id, title, content FROM posts WHERE id = ?'
);
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('Post not found.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $message = 'Please fill in both fields.';
    } else {
        $stmt = $pdo->prepare(
            'UPDATE posts SET title = ?, content = ? WHERE id = ?'
        );
        $stmt->execute([$title, $content, $id]);

        header('Location: posts.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="POST" action="edit_post.php?id=<?= (int) $id ?>">
        <label for="title">Title:</label><br>
        <input
            type="text"
            id="title"
            name="title"
            maxlength="255"
            value="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
            required
        >
        <br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
        <br><br>

        <button type="submit">Save Changes</button>
    </form>

    <p><a href="posts.php">Back to Posts</a></p>
</body>
</html>
