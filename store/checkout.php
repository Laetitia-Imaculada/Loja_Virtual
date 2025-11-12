<?php
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Buscar itens do carrinho
$stmt = $pdo->prepare("
    SELECT c.*, p.nome, p.preco, p.imagem 
    FROM carrinho c 
    JOIN produtos p ON c.produto_id = p.id 
    WHERE c.usuario_id = ?
");
$stmt->execute([$_SESSION['usuario_id']]);
$itens_carrinho = $stmt->fetchAll();

if (empty($itens_carrinho)) {
    header('Location: carrinho.php');
    exit;
}

// Calcular total
$total = 0;
foreach ($itens_carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Processar checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aqui você implementaria:
    // 1. Processamento do pagamento
    // 2. Registro do pedido no banco de dados
    // 3. Limpeza do carrinho
    // 4. Envio de email de confirmação

    // Por enquanto, vamos apenas limpar o carrinho e mostrar sucesso
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);

    $_SESSION['pedido_sucesso'] = true;
    header('Location: pedido_sucesso.php');
    exit;
}

// Buscar dados do usuário
$usuario = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$usuario->execute([$_SESSION['usuario_id']]);
$usuario = $usuario->fetch();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Lay'Clotes</title>
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

    <div class="checkout-container">
        <h1>Finalizar Compra</h1>

        <div class="checkout-content">
            <!-- Resumo do Pedido -->
            <div class="resumo-pedido">
                <h2>Resumo do Pedido</h2>
                <div class="itens-resumo">
                    <?php foreach ($itens_carrinho as $item): ?>
                        <div class="item-resumo">
                            <img src="images/<?php echo $item['imagem']; ?>"
                                alt="<?php echo $item['nome']; ?>"
                                onerror="this.src='images/sem-imagem.jpg'">
                            <div class="item-detalhes">
                                <h4><?php echo $item['nome']; ?></h4>
                                <p>Quantidade: <?php echo $item['quantidade']; ?></p>
                                <p>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="total-resumo">
                    <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
                </div>
            </div>

            <!-- Formulário de Checkout -->
            <div class="form-checkout">
                <h2>Dados de Entrega e Pagamento</h2>

                <form method="POST" id="formCheckout">
                    <!-- Dados de Entrega -->
                    <div class="form-section">
                        <h3>Dados de Entrega</h3>

                        <div class="form-group">
                            <label>Nome Completo *</label>
                            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>CEP *</label>
                                <input type="text" name="cep" id="cep" required maxlength="9">
                            </div>
                            <div class="form-group">
                                <label>Telefone *</label>
                                <input type="tel" name="telefone" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Endereço *</label>
                            <input type="text" name="endereco" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Número *</label>
                                <input type="text" name="numero" required>
                            </div>
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" name="complemento">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Bairro *</label>
                                <input type="text" name="bairro" required>
                            </div>
                            <div class="form-group">
                                <label>Cidade *</label>
                                <input type="text" name="cidade" required>
                            </div>
                            <div class="form-group">
                                <label>Estado *</label>
                                <select name="estado" required>
                                    <option value="">Selecione</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Método de Pagamento -->
                    <div class="form-section">
                        <h3>Método de Pagamento</h3>

                        <div class="metodos-pagamento">
                            <div class="metodo-opcao">
                                <input type="radio" name="metodo_pagamento" value="cartao" id="cartao" required>
                                <label for="cartao">Cartão de Crédito</label>
                            </div>

                            <div class="metodo-opcao">
                                <input type="radio" name="metodo_pagamento" value="boleto" id="boleto">
                                <label for="boleto">Boleto Bancário</label>
                            </div>

                            <div class="metodo-opcao">
                                <input type="radio" name="metodo_pagamento" value="pix" id="pix">
                                <label for="pix">PIX</label>
                            </div>
                        </div>

                        <!-- Dados do Cartão (aparece apenas quando selecionado) -->
                        <div class="dados-cartao" id="dadosCartao" style="display: none;">
                            <div class="form-group">
                                <label>Número do Cartão *</label>
                                <input type="text" name="numero_cartao" placeholder="0000 0000 0000 0000" maxlength="19">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Validade *</label>
                                    <input type="text" name="validade_cartao" placeholder="MM/AA" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label>CVV *</label>
                                    <input type="text" name="cvv_cartao" placeholder="000" maxlength="3">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Nome no Cartão *</label>
                                <input type="text" name="nome_cartao">
                            </div>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <a href="carrinho.php" class="btn btn-voltar">Voltar ao Carrinho</a>
                        <button type="submit" class="btn btn-finalizar">Finalizar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Lay'Clotes. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Mostrar/ocultar dados do cartão
        document.querySelectorAll('input[name="metodo_pagamento"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const dadosCartao = document.getElementById('dadosCartao');
                dadosCartao.style.display = this.value === 'cartao' ? 'block' : 'none';

                // Tornar campos obrigatórios apenas para cartão
                const camposCartao = dadosCartao.querySelectorAll('input');
                camposCartao.forEach(campo => {
                    campo.required = this.value === 'cartao';
                });
            });
        });

        // Formatação do CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });

        // Validação do formulário
        document.getElementById('formCheckout').addEventListener('submit', function(e) {
            const metodoPagamento = document.querySelector('input[name="metodo_pagamento"]:checked');
            if (!metodoPagamento) {
                e.preventDefault();
                alert('Por favor, selecione um método de pagamento.');
                return;
            }
        });
    </script>
</body>

</html>