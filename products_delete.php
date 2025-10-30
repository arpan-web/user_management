<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$id = intval($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
header('Location: dashboard.php');
exit;
