<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lay'Clotes - Sua Loja Online</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Header -->
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Moda que Inspira</h1>
            <p>Descubra as últimas tendências em roupas</p>
            <a href="produtos.php" class="btn">Comprar Agora</a>
        </div>
    </section>

    <!-- Produtos em Destaque -->
    <section class="produtos-destaque">
        <h2>Produtos em Destaque</h2>
        <div class="produtos-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM produtos LIMIT 4");
            while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
                <div class="produto-card">
                    <img src="images/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <form method="POST" action="adicionar_carrinho.php">
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        <button type="submit" class="btn-carrinho">Adicionar ao Carrinho</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Lay'Clotes. Todos os direitos reservados.</p>
    </footer>
</body>

</html>