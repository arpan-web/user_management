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
<link rel="stylesheet" href="css/style.css">
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
