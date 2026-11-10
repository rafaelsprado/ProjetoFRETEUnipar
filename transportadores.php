<?php
require_once 'config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo          = $_POST['codigo_transportadora'] ?? '';
    $cnpj            = $_POST['cnpj'] ?? '';
    $razao_social    = $_POST['razao_social'] ?? '';
    $nome_fantasia   = $_POST['nome_fantasia'] ?? '';
    $telefone        = $_POST['telefone'] ?? '';
    $cep             = $_POST['cep'] ?? '';
    $tipo_logradouro = $_POST['tipo_logradouro'] ?? '';
    $logradouro      = $_POST['logradouro'] ?? '';
    $numero          = $_POST['numero'] ?? '';
    $bairro          = $_POST['bairro'] ?? '';
    $municipio       = $_POST['municipio'] ?? '';
    $estado          = $_POST['estado'] ?? '';

    if (!empty($codigo) && !empty($cnpj) && !empty($razao_social)) {
        $stmt = $pdo->prepare("INSERT INTO transportadores (codigo_transportadora, cnpj, razao_social, nome_fantasia, telefone, cep, tipo_logradouro, logradouro, numero, bairro, municipio, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$codigo, $cnpj, $razao_social, $nome_fantasia, $telefone, $cep, $tipo_logradouro, $logradouro, $numero, $bairro, $municipio, strtoupper($estado)])) {
            $mensagem = '<div class="alert alert-success mt-3">Transportadora cadastrada com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">Erro ao cadastrar transportadora.</div>';
        }
    }
}

$transportadores_lista = $pdo->query("SELECT * FROM transportadores ORDER BY id DESC")->fetchAll();

include 'includes/header.php';
?>

<div class="card p-4 shadow-sm border-0 mb-4">
    <form method="POST" action="transportadores.php">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="submit" class="btn btn-custom-action px-4">Salvar Transportadora</button>
            <h2 class="h3 fw-bold mb-0 text-center flex-grow-1">Cadastro de Transportadora</h2>
            <div style="width: 170px;"></div>
        </div>

        <?= $mensagem ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Código da Transportadora:</label>
                <input type="text" name="codigo_transportadora" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">CNPJ:</label>
                <input type="text" name="cnpj" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Razão Social:</label>
                <input type="text" name="razao_social" class="form-control" required>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Nome Fantasia:</label>
                <input type="text" name="nome_fantasia" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Telefone:</label>
                <input type="text" name="telefone" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">CEP:</label>
                <input type="text" name="cep" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Tipo de Logradouro:</label>
                <input type="text" name="tipo_logradouro" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Nome do Logradouro:</label>
                <input type="text" name="logradouro" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Número:</label>
                <input type="text" name="numero" class="form-control">
            </div>
            <div class="col-md-9">
                <label class="form-label fw-bold">Bairro:</label>
                <input type="text" name="bairro" class="form-control">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Município:</label>
                <input type="text" name="municipio" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Estado:</label>
                <input type="text" name="estado" maxlength="2" class="form-control">
            </div>
        </div>
    </form>
</div>

<?php if (!empty($transportadores_lista)): ?>
<div class="card p-4 shadow-sm border-0">
    <h4 class="fw-bold mb-3">Transportadoras Cadastradas</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>CNPJ</th>
                    <th>Razão Social</th>
                    <th>Nome Fantasia</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transportadores_lista as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['codigo_transportadora']) ?></td>
                    <td><?= htmlspecialchars($t['cnpj']) ?></td>
                    <td><?= htmlspecialchars($t['razao_social']) ?></td>
                    <td><?= htmlspecialchars($t['nome_fantasia']) ?></td>
                    <td><?= htmlspecialchars($t['telefone']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
