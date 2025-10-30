<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard Admin Gudang</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .dashboard {
      width: 90%;
      max-width: 1000px;
      background: #fff;
      margin: 40px auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.5s ease-in-out;
    }

    h2 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 25px;
    }

    .user-info {
      text-align: center;
      margin-bottom: 25px;
      font-size: 16px;
    }

    .user-info a {
      margin: 0 8px;
      color: #007bff;
      text-decoration: none;
      font-weight: 600;
    }

    .user-info a:hover {
      text-decoration: underline;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background: #007bff;
      color: white;
      text-transform: uppercase;
      font-size: 14px;
    }

    tr:hover {
      background-color: #f5f9ff;
    }

    .add-btn {
      display: inline-block;
      background: #28a745;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    .add-btn:hover {
      background: #218838;
      transform: scale(1.02);
    }

    .action a {
      color: #007bff;
      font-weight: 600;
      text-decoration: none;
    }

    .action a:hover {
      text-decoration: underline;
    }

    hr {
      border: none;
      height: 1px;
      background: #ccc;
      margin: 25px 0;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Dashboard Admin Gudang</h2>
    <div class="user-info">
      Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong> |
      <a href="profile.php">Profil</a> |
      <a href="change_password.php">Ubah Password</a> |
      <a href="logout.php">Logout</a>
    </div>

    <hr>

    <h3>Data Produk</h3>
    <p><a href="products_add.php" class="add-btn">+ Tambah Produk</a></p>

    <table>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>
      <?php
      $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
      while ($row = $stmt->fetch()) {
          echo "<tr>";
          echo "<td>{$row['id']}</td>";
          echo "<td>" . htmlspecialchars($row['name']) . "</td>";
          echo "<td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>";
          echo "<td>{$row['stock']}</td>";
          echo "<td class='action'>
                  <a href='products_edit.php?id={$row['id']}'>Edit</a> |
                  <a href='products_delete.php?id={$row['id']}' onclick=\"return confirm('Hapus produk ini?')\">Hapus</a>
                </td>";
          echo "</tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
