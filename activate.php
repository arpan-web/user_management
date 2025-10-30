<?php
require 'db.php';
require 'functions.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE activation_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $pdo->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE id = ?")
            ->execute([$user['id']]);
        $message = "✅ Akun berhasil diaktifkan. Silakan login.";
        $status = "success";
    } else {
        $message = "❌ Token aktivasi tidak valid.";
        $status = "error";
    }
} else {
    $message = "⚠️ Token tidak ditemukan.";
    $status = "error";
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Aktivasi Akun</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Aktivasi Akun</h2>
        <p class="<?php echo $status; ?>"><?php echo htmlspecialchars($message); ?></p>
        <a href="login.php" class="btn">Ke Halaman Login</a>
    </div>
</div>
</body>
</html>
