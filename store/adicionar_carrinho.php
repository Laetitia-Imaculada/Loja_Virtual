<?php
session_start();
include 'config.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_POST['produto_id'])) {
    $produto_id = $_POST['produto_id'];
    
    $stmt = $pdo->prepare("INSERT INTO carrinho (usuario_id, produto_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['usuario_id'], $produto_id]);
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>