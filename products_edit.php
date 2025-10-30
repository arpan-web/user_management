<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { 
    echo "Produk tidak ditemukan"; 
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?");
    $stmt->execute([$name, $desc, $price, $stock, $id]);
    header('Location: dashboard.php'); 
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Produk</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Produk</h2>
    <form method="post">
        <label>Nama Produk</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Deskripsi</label>
        <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>

        <label>Harga (Rp)</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

        <label>Stok</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>
