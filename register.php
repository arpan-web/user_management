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
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7fc;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .container {
        background: #fff;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        width: 400px;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }
    input[type="email"],
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 14px;
    }
    button {
        width: 100%;
        background: #007bff;
        color: #fff;
        border: none;
        padding: 12px;
        font-size: 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background: #0056b3;
    }
    .message {
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 15px;
    }
    .message.error {
        background: #ffe6e6;
        color: #cc0000;
        border: 1px solid #ffb3b3;
    }
    .message.success {
        background: #e6ffe6;
        color: #009900;
        border: 1px solid #99ff99;
    }
    p {
        text-align: center;
        margin-top: 15px;
    }
    a {
        color: #007bff;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
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
