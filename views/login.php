<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    if ($user->login($_POST['email'], $_POST['password'])) {
        header('Location: ' . BASE_URL . 'dashboard/dashboard.php');
    } else {
        echo 'Invalid login credentials';
    }
}
?>
