<?php
require_once 'config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transportador_id = $_POST['transportador_id'] ?? '';
    $origem_id        = $_POST['origem_id'] ?? '';
    $destino_id       = $_POST['destino_id'] ?? '';
    $valor_frete      = $_POST['valor_frete'] ?? 0;
    $produtos_ids     = $_POST['produtos'] ?? [];
    $data_frete       = date('Y-m-d');

    if (!empty($transportador_id) && !empty($origem_id) && !empty($destino_id) && !empty($produtos_ids)) {
        $stmt = $pdo->prepare("INSERT INTO fretes (transportador_id, origem_id, destino_id, valor_frete, data_frete) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$transportador_id, $origem_id, $destino_id, $valor_frete, $data_frete])) {
            $frete_id = $pdo->lastInsertId();
            $stmt_prod = $pdo->prepare("INSERT INTO frete_produtos (frete_id, produto_id) VALUES (?, ?)");
            foreach ($produtos_ids as $p_id) {
                $stmt_prod->execute([$frete_id, $p_id]);
            }
            $mensagem = '<div class="alert alert-success mt-3">Frete N° ' . $frete_id . ' gerado com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">Erro ao gerar frete.</div>';
        }
    } else {
        $mensagem = '<div class="alert alert-warning mt-3">Preencha todos os campos e selecione pelo menos um produto.</div>';
    }
}

$transportadores = $pdo->query("SELECT * FROM transportadores ORDER BY razao_social ASC")->fetchAll();
$enderecos       = $pdo->query("SELECT * FROM enderecos ORDER BY municipio ASC")->fetchAll();
$produtos        = $pdo->query("SELECT * FROM produtos ORDER BY descricao ASC")->fetchAll();

include 'includes/header.php';
?>

<div class="card p-4 shadow-sm border-0 mb-4">
    <form method="POST" action="fretes.php">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="submit" class="btn btn-custom-action px-4">Salvar Frete</button>
            <h2 class="h3 fw-bold mb-0 text-center flex-grow-1">Gerar Frete</h2>
            <div style="width: 140px;"></div>
        </div>

        <?= $mensagem ?>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-bold">Transportadora:</label>
                <select name="transportador_id" class="form-select" required>
                    <option value="">Selecione uma transportadora...</option>
                    <?php foreach ($transportadores as $t): ?>
                        <option value="<?= $t['id'] ?>">
                            [Cód: <?= htmlspecialchars($t['codigo_transportadora']) ?>] <?= htmlspecialchars($t['razao_social']) ?> (Fantasia: <?= htmlspecialchars($t['nome_fantasia']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <<div class="col-md-6">
                <label class="form-label fw-bold">Endereço de Origem (Município/Estado):</label>
                <select name="origem_id" class="form-select" required>
                    <option value="">Selecione a origem...</option>
                    <?php foreach ($enderecos as $e): ?>
                        <option value="<?= $e['id'] ?>">
                            <?= htmlspecialchars($e['logradouro'] . ', ' . $e['numero'] . ' - ' . $e['municipio'] . '/' . $e['estado']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Endereço de Destino (Município/Estado):</label>
                <select name="destino_id" class="form-select" required>
                    <option value="">Selecione o destino...</option>
                    <?php foreach ($enderecos as $e): ?>
                        <option value="<?= $e['id'] ?>">
                            <?= htmlspecialchars($e['logradouro'] . ', ' . $e['numero'] . ' - ' . $e['municipio'] . '/' . $e['estado']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold">Adicionar Produtos ao Frete (Ligação 1 para Muitos):</label>
                <div class="input-group">
                    <select id="select_produto" class="form-select">
                        <option value="">Selecione um produto para incluir...</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p['id'] ?>" data-codigo="<?= htmlspecialchars($p['codigo_produto']) ?>" data-nome="<?= htmlspecialchars($p['descricao']) ?>" data-valor="<?= $p['valor'] ?>">
                                [Cód: <?= htmlspecialchars($p['codigo_produto']) ?>] <?= htmlspecialchars($p['descricao']) ?> - R$ <?= number_format($p['valor'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-secondary" onclick="adicionarProduto()">+ Adicionar Produto</button>
                </div>
            </div>

            <div class="col-md-12">
                <table class="table table-bordered mt-2" id="tabela_produtos">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nome do Produto</th>
                            <th>Valor Unitário</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Itens inseridos via JS -->
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Valor do FRETE (R$):</label>
                <input type="number" step="0.01" name="valor_frete" class="form-control" placeholder="0.00" required>
            </div>
        </div>
    </form>
</div>

<script>
function adicionarProduto() {
    const select = document.getElementById('select_produto');
    const prodId = select.value;
    if (!prodId) return;

    const option = select.options[select.selectedIndex];
    const codigo = option.getAttribute('data-codigo');
    const nome = option.getAttribute('data-nome');
    const valor = parseFloat(option.getAttribute('data-valor')).toFixed(2);

    const tbody = document.querySelector('#tabela_produtos tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${codigo}</td>
        <td>${nome} <input type="hidden" name="produtos[]" value="${prodId}"></td>
        <td>R$ ${valor.replace('.', ',')}</td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Remover</button></td>
    `;
    tbody.appendChild(tr);
}
</script>

<?php include 'includes/footer.php'; ?>
