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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #444;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5563DE;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #4453c4;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success-box {
            background-color: #e9ffe9;
            color: #2b8a3e;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 16px;
            margin: 20px;
        }
        a {
            color: #5563DE;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
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
