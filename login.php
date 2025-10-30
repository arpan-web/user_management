<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_active']) {
            // set session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['fullname'] = $user['fullname'];
            header('Location: dashboard.php');
            exit;
        } else {
            $err = "Akun belum aktif. Cek email untuk link aktivasi.";
        }
    } else {
        $err = "Email atau password salah.";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login Admin Gudang</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="login-container">
    <h2>Login Admin Gudang</h2>
    <?php if (!empty($err)): ?>
      <div class="error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post">
      <label>Email:</label>
      <input type="email" name="email" placeholder="Masukkan email" required>

      <label>Password:</label>
      <input type="password" name="password" placeholder="Masukkan password" required>

      <button type="submit">Login</button>
    </form>

    <p>
      <a href="register.php">Daftar</a> | 
      <a href="forgot.php">Lupa Password?</a>
    </p>
  </div>

</body>
</html>
