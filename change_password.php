<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch();

    if (!$row || !password_verify($current, $row['password'])) {
        $err = "❌ Password saat ini salah.";
    } elseif ($new !== $confirm) {
        $err = "⚠️ Konfirmasi password baru tidak cocok.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hash, $userId]);
        $success = "✅ Password berhasil diubah.";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Ubah Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Ubah Password</h2>

        <?php if (!empty($err)) echo "<p class='error'>$err</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

        <form method="post">
            <label for="current">Password Saat Ini</label>
            <input type="password" id="current" name="current" required>

            <label for="new">Password Baru</label>
            <input type="password" id="new" name="new" required>

            <label for="confirm">Konfirmasi Password Baru</label>
            <input type="password" id="confirm" name="confirm" required>

            <button type="submit">Ubah Password</button>
        </form>

        <p class="link"><a href="dashboard.php">← Kembali ke Dashboard</a></p>
    </div>
</div>
</body>
</html>
