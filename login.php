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
<style>
  body {
    background: linear-gradient(135deg, #3498db, #2ecc71);
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
  }

  .login-container {
    max-width: 420px;
    margin: 100px auto;
    background: #fff;
    border-radius: 15px;
    padding: 35px 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    animation: fadeIn 0.5s ease-in-out;
  }

  h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 20px;
  }

  form {
    display: flex;
    flex-direction: column;
  }

  input[type="email"], input[type="password"] {
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: 0.3s;
  }

  input:focus {
    border-color: #2980b9;
    box-shadow: 0 0 6px rgba(41, 128, 185, 0.3);
    outline: none;
  }

  button {
    background: #2980b9;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 15px;
    transition: 0.3s;
  }

  button:hover {
    background: #3498db;
    transform: scale(1.02);
  }

  p {
    text-align: center;
    margin-top: 18px;
  }

  a {
    color: #2980b9;
    text-decoration: none;
    font-weight: 600;
  }

  a:hover {
    text-decoration: underline;
  }

  .error {
    background: #ffe0e0;
    color: #c0392b;
    border: 1px solid #e74c3c;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
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
