<?php
require_once 'config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo    = $_POST['codigo_produto'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $especie   = $_POST['especie'] ?? '';
    $peso      = $_POST['peso'] ?? 0;
    $valor     = $_POST['valor'] ?? 0;

    if (!empty($codigo) && !empty($descricao)) {
        $stmt = $pdo->prepare("INSERT INTO produtos (codigo_produto, descricao, especie, peso, valor) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$codigo, $descricao, $especie, $peso, $valor])) {
            $mensagem = '<div class="alert alert-success mt-3">Produto cadastrado com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">Erro ao cadastrar produto.</div>';
        }
    }
}

// Busca produtos para listagem
$produtos_lista = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();

include 'includes/header.php';
?>

<div class="card p-4 shadow-sm border-0 mb-4">
    <form method="POST" action="produtos.php">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="submit" class="btn btn-custom-action px-4">Salvar Produto</button>
            <h2 class="h3 fw-bold mb-0 text-center flex-grow-1">Cadastro de Produtos</h2>
            <div style="width: 140px;"></div>
        </div>

        <?= $mensagem ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Código do Produto:</label>
                <input type="text" name="codigo_produto" class="form-control" required>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Descrição (nome):</label>
                <input type="text" name="descricao" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Espécie:</label>
                <input type="text" name="especie" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Peso do produto (KG):</label>
                <input type="number" step="0.01" name="peso" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Valor:</label>
                <input type="number" step="0.01" name="valor" class="form-control" required>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($produtos_lista)): ?>
<div class="card p-4 shadow-sm border-0">
    <h4 class="fw-bold mb-3">Produtos Cadastrados</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Espécie</th>
                    <th>Peso (kg)</th>
                    <th>Valor (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos_lista as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['codigo_produto']) ?></td>
                    <td><?= htmlspecialchars($p['descricao']) ?></td>
                    <td><?= htmlspecialchars($p['especie']) ?></td>
                    <td><?= number_format($p['peso'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
