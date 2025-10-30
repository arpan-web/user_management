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
        margin-top: 10px;
        margin-bottom: 5px;
    }
    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 14px;
        resize: none;
    }
    textarea {
        height: 80px;
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
