<?php
require 'db.php';

// Cek token
if (!isset($_GET['token'])) {
    echo "Token tidak ditemukan.";
    exit;
}

$token = $_GET['token'];

// Ambil user berdasarkan token
$stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user || strtotime($user['reset_expires']) < time()) {
    echo "Token tidak valid atau sudah kadaluarsa.";
    exit;
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($new !== $confirm) {
        $err = "Konfirmasi password tidak cocok.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?")
            ->execute([$hash, $user['id']]);
        echo "<div class='success-box'>Password berhasil direset. <a href='login.php'>Login</a></div>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="reset-container">
    <h2>Reset Password</h2>
    <?php if (!empty($err)) echo "<div class='error'>$err</div>"; ?>

    <form method="post">
        <label>Password Baru:</label>
        <input type="password" name="password" required>

        <label>Konfirmasi Password:</label>
        <input type="password" name="confirm" required>

        <button type="submit">Reset Password</button>
    </form>
</div>
</body>
</html>
