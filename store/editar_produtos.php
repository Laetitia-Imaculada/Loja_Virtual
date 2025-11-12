<?php
session_start();
include 'config.php';

// Atualizar nome do produto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto_id']) && isset($_POST['novo_nome'])) {
    $produto_id = (int)$_POST['produto_id'];
    $novo_nome = trim($_POST['novo_nome']);
    $nova_descricao = trim($_POST['nova_descricao'] ?? '');
    $nova_categoria = trim($_POST['nova_categoria'] ?? '');

    if (!empty($novo_nome)) {
        $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, categoria = ? WHERE id = ?");
        $stmt->execute([$novo_nome, $nova_descricao, $nova_categoria, $produto_id]);
        $sucesso = "Produto atualizado com sucesso!";
    }
}

// Buscar todos os produtos
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos - Lay'Clotes</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .editar-container {
            padding: 2rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .produtos-editar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .produto-editar-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .produto-editar-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 2px solid #ddd;
        }

        .form-editar {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .form-editar input,
        .form-editar textarea,
        .form-editar select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .form-editar textarea {
            min-height: 60px;
            resize: vertical;
        }

        .btn-salvar {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-salvar:hover {
            background: #219a52;
        }

        .alerta-sucesso {
            background: #27ae60;
            color: white;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .produto-id {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
    </style>
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
                <li><a href="editar_produtos.php">Editar Produtos</a></li>
            </ul>
        </nav>
    </header>

    <div class="editar-container">
        <h1>Editar Produtos</h1>
        <p>Visualize cada imagem e edite o nome, descrição e categoria conforme necessário.</p>

        <?php if (isset($sucesso)): ?>
            <div class="alerta-sucesso"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <div class="produtos-editar-grid">
            <?php foreach ($produtos as $produto): ?>
                <div class="produto-editar-card">
                    <div class="produto-id">ID: <?php echo $produto['id']; ?> | Imagem: <?php echo htmlspecialchars($produto['imagem']); ?></div>
                    <img src="images/<?php echo htmlspecialchars($produto['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        onerror="this.src='images/sem-imagem.jpg'">

                    <form method="POST" class="form-editar">
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">

                        <label><strong>Nome do Produto:</strong></label>
                        <input type="text" name="novo_nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

                        <label><strong>Descrição:</strong></label>
                        <textarea name="nova_descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

                        <label><strong>Categoria:</strong></label>
                        <input type="text" name="nova_categoria" value="<?php echo htmlspecialchars($produto['categoria']); ?>"
                            placeholder="Ex: Camisetas, Calças, Vestidos...">

                        <button type="submit" class="btn-salvar">💾 Salvar Alterações</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Lay'Clotes. Todos os direitos reservados.</p>
    </footer>
</body>

</html>