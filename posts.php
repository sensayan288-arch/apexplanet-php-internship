
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/database.php';

$stmt = $pdo->query(
    'SELECT id, title, content, created_at FROM posts ORDER BY id DESC'
);

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Posts</title>
</head>
<body>
    <h1>All Posts</h1>

    <p><a href="create_post.php">Create a New Post</a></p>
    <p><a href="dashboard.php">Back to Dashboard</a></p>

    <?php if (empty($posts)): ?>
        <p>No posts found. Create your first post!</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article>
                <h2><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h2>

                <p><?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')) ?></p>

                <small>
                    Created: <?= htmlspecialchars($post['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </small>
                
<form method="POST" action="delete_post.php"
      onsubmit="return confirm('Are you sure you want to delete this post?');">
    <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
    <button type="submit">Delete</button>
</form>


                <hr>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
