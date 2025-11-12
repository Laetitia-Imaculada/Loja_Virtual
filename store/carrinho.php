<?php
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuarioId = (int)$_SESSION['usuario_id'];

// Remover item
if (isset($_POST['remover']) && isset($_POST['carrinho_id'])) {
    $carrinhoId = (int)$_POST['carrinho_id'];
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$carrinhoId, $usuarioId]);
}

// Atualizar quantidade
if (isset($_POST['atualizar_quantidade']) && isset($_POST['carrinho_id']) && isset($_POST['quantidade'])) {
    $carrinhoId = (int)$_POST['carrinho_id'];
    $quantidade = max(0, (int)$_POST['quantidade']);
    if ($quantidade > 0) {
        $stmt = $pdo->prepare("UPDATE carrinho SET quantidade = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$quantidade, $carrinhoId, $usuarioId]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$carrinhoId, $usuarioId]);
    }
}

// Buscar itens do carrinho
$stmt = $pdo->prepare("SELECT c.id AS carrinho_id, c.quantidade, p.id AS produto_id, p.nome, p.preco, p.imagem
                       FROM carrinho c
                       JOIN produtos p ON p.id = c.produto_id
                       WHERE c.usuario_id = ?");
$stmt->execute([$usuarioId]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0.0;
foreach ($itens as $i) {
    $total += ((float)$i['preco']) * ((int)$i['quantidade']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Lay'Clotes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h2>Lay'Clotes</h2>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="carrinho.php">Carrinho</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <div class="carrinho-container">
        <h1>Seu Carrinho</h1>

        <div class="carrinho-itens">
            <?php if (empty($itens)): ?>
                <p>Seu carrinho está vazio.</p>
                <p><a class="btn" href="produtos.php">Ver produtos</a></p>
            <?php else: ?>
                <?php foreach ($itens as $item): ?>
                    <div class="carrinho-item">
                        <img src="images/<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" onerror="this.src='images/sem-imagem.jpg'">
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <p>Preço: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                        </div>
                        <form method="POST" style="display:flex; align-items:center; gap:0.5rem;">
                            <input type="hidden" name="carrinho_id" value="<?php echo (int)$item['carrinho_id']; ?>">
                            <input type="number" name="quantidade" value="<?php echo (int)$item['quantidade']; ?>" min="0" style="width:80px;">
                            <button type="submit" name="atualizar_quantidade" class="btn">Atualizar</button>
                            <button type="submit" name="remover" class="btn-remover">Remover</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($itens)): ?>
            <div class="carrinho-total">
                <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
                <div style="margin-top:1rem; display:flex; gap:1rem; justify-content:flex-end; flex-wrap:wrap;">
                    <a class="btn" href="produtos.php">Continuar Comprando</a>
                    <a class="btn" href="checkout.php">Ir para o Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Lay'Clotes. Todos os direitos reservados.</p>
    </footer>
</body>

</html>