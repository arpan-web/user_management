<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $pdo->prepare("UPDATE users SET fullname = ? WHERE id = ?")->execute([$fullname, $userId]);
    $_SESSION['fullname'] = $fullname;
    header('Location: profile.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Profil Pengguna</title>
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
        margin-bottom: 25px;
        color: #333;
    }
    label {
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }
    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 14px;
    }
    .info {
        background: #f1f1f1;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 15px;
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
        margin-top: 20px;
        transition: 0.3s;
    }
    button:hover {
        background: #0056b3;
    }
    a {
        text-decoration: none;
        color: #007bff;
        display: block;
        text-align: center;
        margin-top: 15px;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Profil Pengguna</h2>
    <form method="post">
        <label>Email (Username)</label>
        <div class="info"><?php echo htmlspecialchars($user['email']); ?></div>

        <label>Nama Lengkap</label>
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>
