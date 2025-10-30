<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?,?,?,?)");
    $stmt->execute([$name, $desc, $price, $stock]);
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Produk</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Produk</h2>
    <form method="post">
        <label>Nama Produk</label>
        <input type="text" name="name" required>

        <label>Deskripsi</label>
        <textarea name="description"></textarea>

        <label>Harga (Rp)</label>
        <input type="number" step="0.01" name="price" required>

        <label>Stok</label>
        <input type="number" name="stock" required>

        <button type="submit">Simpan Produk</button>
    </form>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>
