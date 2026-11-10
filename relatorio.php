<?php
require_once 'config/db.php';

$data_filtro = $_GET['data'] ?? date('Y-m-d');
$frete_id_filtro = $_GET['frete_id'] ?? '';

// Download em CSV se solicitado
if (isset($_GET['download']) && $_GET['download'] === '1') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=relatorio_fretes_' . $data_filtro . '.csv');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($output, ['ID Frete', 'Data', 'Transportadora', 'Origem', 'Destino', 'Valor Frete', 'Produtos']);

    $stmt = $pdo->prepare("
        SELECT f.id, f.data_frete, t.razao_social, 
               CONCAT(eo.municipio, '/', eo.estado) AS origem,
               CONCAT(ed.municipio, '/', ed.estado) AS destino,
               f.valor_frete,
               GROUP_CONCAT(p.descricao SEPARATOR '; ') AS produtos
        FROM fretes f
        JOIN transportadores t ON f.transportador_id = t.id
        JOIN enderecos eo ON f.origem_id = eo.id
        JOIN enderecos ed ON f.destino_id = ed.id
        LEFT JOIN frete_produtos fp ON f.id = fp.frete_id
        LEFT JOIN produtos p ON fp.produto_id = p.id
        WHERE f.data_frete = ?
        GROUP BY f.id
    ");
    $stmt->execute([$data_filtro]);

    while ($row = $stmt->fetch()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Consulta Fretes para exibição
$sql = "
    SELECT f.id AS numero_frete, f.data_frete, f.valor_frete,
           t.codigo_transportadora, t.razao_social AS nome_transportadora, t.nome_fantasia,
           eo.municipio AS origem_municipio, eo.estado AS origem_estado,
           ed.municipio AS destino_municipio, ed.estado AS destino_estado,
           GROUP_CONCAT(CONCAT(p.codigo_produto, ' - ', p.descricao) SEPARATOR '<br>') AS lista_produtos
    FROM fretes f
    JOIN transportadores t ON f.transportador_id = t.id
    JOIN enderecos eo ON f.origem_id = eo.id
    JOIN enderecos ed ON f.destino_id = ed.id
    LEFT JOIN frete_produtos fp ON f.id = fp.frete_id
    LEFT JOIN produtos p ON fp.produto_id = p.id
    WHERE 1=1
";

$params = [];
if (!empty($frete_id_filtro)) {
    $sql .= " AND f.id = ?";
    $params[] = $frete_id_filtro;
} else {
    $sql .= " AND f.data_frete = ?";
    $params[] = $data_filtro;
}

$sql .= " GROUP BY f.id ORDER BY f.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$fretes_resultado = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="card p-4 shadow-sm border-0 mb-4">
    <form method="GET" action="relatorio.php">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="submit" class="btn btn-custom-action px-4">Consultar Frete</button>
            <h2 class="h3 fw-bold mb-0 text-center flex-grow-1">Relatório de Frete</h2>
            <a href="relatorio.php?data=<?= $data_filtro ?>&download=1" class="btn btn-success font-weight-bold">Baixar Relatório (CSV)</a>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Número do Frete (Opcional):</label>
                <input type="text" name="frete_id" class="form-control" value="<?= htmlspecialchars($frete_id_filtro) ?>" placeholder="Ex: 1">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Data do Frete:</label>
                <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data_filtro) ?>">
            </div>
        </div>
    </form>
</div>

<?php if (!empty($fretes_resultado)): ?>
    <?php foreach ($fretes_resultado as $f): ?>
    <div class="card p-4 shadow-sm border-0 mb-3 bg-white">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Número do Frete:</label>
                <input type="text" class="form-control bg-light" value="<?= $f['numero_frete'] ?>" readonly>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Nome da Transportadora:</label>
                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($f['nome_transportadora']) ?>" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Valor do Frete:</label>
                <input type="text" class="form-control bg-light" value="R$ <?= number_format($f['valor_frete'], 2, ',', '.') ?>" readonly>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Produtos Vinculados:</label>
                <div class="p-2 bg-light border rounded"><?= $f['lista_produtos'] ?: 'Nenhum produto cadastrado' ?></div>
            </div>

            <div class="col-md-12 mt-3">
                <h6 class="fw-bold text-secondary">Endereço de Origem:</h6>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Município:</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($f['origem_municipio']) ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estado:</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($f['origem_estado']) ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <h6 class="fw-bold text-secondary">Endereço de Destino:</h6>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Município:</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($f['destino_municipio']) ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estado:</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($f['destino_estado']) ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">Nenhum frete encontrado para os filtros selecionados.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
