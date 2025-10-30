<?php
require 'db.php';
require 'functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $fullname = trim($_POST['fullname']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (!$email) {
        $err = "Email tidak valid.";
    } elseif ($password !== $confirm) {
        $err = "Password dan konfirmasi tidak cocok.";
    } else {
        // cek apakah email sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $err = "Email sudah terdaftar.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $activation_token = bin2hex(random_bytes(16));
            $stmt = $pdo->prepare("INSERT INTO users (email, password, fullname, activation_token) VALUES (?,?,?,?)");
            $stmt->execute([$email, $hash, $fullname, $activation_token]);

            // kirim email aktivasi
            $link = "http://localhost/user_management/activate.php?token=$activation_token";
            $body = "<p>Halo $fullname,</p>
                     <p>Silakan klik link berikut untuk mengaktifkan akun Anda:</p>
                     <p><a href='$link'>$link</a></p>";

            if (sendEmail($email, $fullname, 'Aktivasi Akun — Admin Gudang', $body)) {
                $success = "Registrasi berhasil. Silakan cek email untuk aktivasi akun Anda.";
            } else {
                $err = "Registrasi tersimpan, tapi gagal mengirim email aktivasi.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Registrasi Admin Gudang</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Registrasi Admin Gudang</h2>

    <?php if (!empty($err)) echo "<div class='message error'>$err</div>"; ?>
    <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email (username)" required>
        <input type="text" name="fullname" placeholder="Nama Lengkap" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm" placeholder="Konfirmasi Password" required>
        <button type="submit">Daftar</button>
    </form>

    <p><a href="login.php">Sudah punya akun? Login</a></p>
</div>
</body>
</html>
