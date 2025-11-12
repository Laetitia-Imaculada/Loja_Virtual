<?php
session_start();
include 'config.php';

// Filtros e busca
$categoria = $_GET['categoria'] ?? '';
$busca = $_GET['busca'] ?? '';

// Construir query
$sql = "SELECT * FROM produtos WHERE 1=1";
$params = [];

if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
}

if (!empty($busca)) {
    $sql .= " AND (nome LIKE ? OR descricao LIKE ?)";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
}

$sql .= " ORDER BY nome";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();

// Buscar categorias únicas
$categorias = $pdo->query("SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Lay'Clotes</title>
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

    <div class="produtos-container">
        <h1>Nossos Produtos</h1>

        <!-- Filtros e Busca -->
        <div class="filtros">
            <form method="GET" class="filtros-form">
                <div class="form-group">
                    <input type="text" name="busca" placeholder="Buscar produtos..." value="<?php echo htmlspecialchars($busca); ?>">
                </div>

                <div class="form-group">
                    <select name="categoria">
                        <option value="">Todas as categorias</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['categoria']; ?>"
                                <?php echo ($categoria == $cat['categoria']) ? 'selected' : ''; ?>>
                                <?php echo $cat['categoria']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn">Filtrar</button>
                <a href="produtos.php" class="btn btn-limpar">Limpar Filtros</a>
            </form>
        </div>

        <!-- Lista de Produtos -->
        <div class="produtos-grid">
            <?php if (empty($produtos)): ?>
                <div class="sem-produtos">
                    <p>Nenhum produto encontrado.</p>
                </div>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="produto-card">
                        <div class="produto-imagem">
                            <img src="images/<?php echo $produto['imagem']; ?>"
                                alt="<?php echo $produto['nome']; ?>"
                                onerror="this.src='images/sem-imagem.jpg'">
                        </div>
                        <div class="produto-info">
                            <h3><?php echo $produto['nome']; ?></h3>
                            <p class="produto-descricao"><?php echo $produto['descricao']; ?></p>
                            <p class="produto-categoria"><?php echo $produto['categoria']; ?></p>
                            <p class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <form method="POST" action="adicionar_carrinho.php" class="form-carrinho">
                                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                                    <button type="submit" class="btn-carrinho">
                                        🛒 Adicionar ao Carrinho
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="login.php" class="btn-carrinho">
                                    🛒 Faça login para comprar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Lay'Clotes. Todos os direitos reservados.</p>
    </footer>
</body>

</html>