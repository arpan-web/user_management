<?php
require 'db.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    if ($email) {
        $stmt = $pdo->prepare("SELECT id, fullname FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
            $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?")
                ->execute([$token, $expires, $user['id']]);

            $link = "http://localhost/user_management/reset.php?token=$token";
            $body = "<p>Halo {$user['fullname']},</p>
                     <p>Klik link berikut untuk reset password Anda (berlaku selama 1 jam): 
                     <a href='$link'>$link</a></p>";

            if (sendEmail($email, $user['fullname'], 'Reset Password — Admin Gudang', $body)) {
                $success = "✅ Link reset password telah dikirim ke email Anda.";
            } else {
                $err = "❌ Gagal mengirim email. Coba lagi nanti.";
            }
        } else {
            $err = "⚠️ Email tidak ditemukan.";
        }
    } else {
        $err = "⚠️ Email tidak valid.";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Lupa Password</h2>

        <?php if (!empty($err)) echo "<p class='error'>$err</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

        <form method="post">
            <label for="email">Masukkan Email</label>
            <input type="email" id="email" name="email" placeholder="email@example.com" required>

            <button type="submit">Kirim Link Reset</button>
        </form>

        <p class="link"><a href="login.php">← Kembali ke Login</a></p>
    </div>
</div>
</body>
</html>
