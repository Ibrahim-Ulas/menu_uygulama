<?php
session_start();

// Sabit bir kullanıcı adı ve şifre tanımlayın (örnek için)
$adminUsername = 'admin';
$adminPassword = '12345';

// Giriş kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['is_admin'] = true; // Admin oturumunu işaretle
        header('Location: admin.php'); // Admin sayfasına yönlendir
        exit;
    } else {
        $error = 'Hatalı kullanıcı adı veya şifre.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi</title>
    <style>
        body{
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Admin Girişi</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Şifre:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Giriş Yap</button>
    </form>
</body>
</html>
