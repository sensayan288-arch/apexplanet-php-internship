
<?php
require_once __DIR__ . '/config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Please fill in all fields.';
    } elseif (strlen($password) < 8) {
        $message = 'Password must be at least 8 characters.';
    } else {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO users (username, password)
                 VALUES (?, ?)'
            );

            $stmt->execute([$username, $passwordHash]);

            $message = 'Registration successful!';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $message = 'Username already exists.';
            } else {
                $message = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
</head>
<body>
    <h1>Create an Account</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input
            type="text"
            id="username"
            name="username"
            maxlength="100"
            required
        >

        <br><br>

        <label for="password">Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            required
        >

        <br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
