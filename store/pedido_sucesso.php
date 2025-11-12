<?php
session_start();
if (!isset($_SESSION['pedido_sucesso'])) {
    header('Location: index.php');
    exit;
}

unset($_SESSION['pedido_sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Lay'Clotes</title>
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
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="sucesso-container">
        <div class="sucesso-content">
            <div class="sucesso-icon">✅</div>
            <h1>Pedido Confirmado!</h1>
            <p>Seu pedido foi realizado com sucesso.</p>
            <p>Em breve você receberá um email com os detalhes da compra.</p>
            <p><strong>Obrigado por comprar na Lay'Clotes!</strong></p>

            <div class="sucesso-actions">
                <a href="index.php" class="btn">Continuar Comprando</a>
                <a href="produtos.php" class="btn btn-secundario">Ver Mais Produtos</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Lay'Clotes. Todos os direitos reservados.</p>
    </footer>
</body>

</html>