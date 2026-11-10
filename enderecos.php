<?php
require_once 'config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cep             = $_POST['cep'] ?? '';
    $tipo_logradouro = $_POST['tipo_logradouro'] ?? '';
    $logradouro      = $_POST['logradouro'] ?? '';
    $numero          = $_POST['numero'] ?? '';
    $bairro          = $_POST['bairro'] ?? '';
    $municipio       = $_POST['municipio'] ?? '';
    $estado          = $_POST['estado'] ?? '';

    if (!empty($cep) && !empty($logradouro) && !empty($municipio) && !empty($estado)) {
        $stmt = $pdo->prepare("INSERT INTO enderecos (cep, tipo_logradouro, logradouro, numero, bairro, municipio, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$cep, $tipo_logradouro, $logradouro, $numero, $bairro, $municipio, strtoupper($estado)])) {
            $mensagem = '<div class="alert alert-success mt-3">Endereço cadastrado com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">Erro ao cadastrar endereço.</div>';
        }
    }
}

$enderecos_lista = $pdo->query("SELECT * FROM enderecos ORDER BY id DESC")->fetchAll();

include 'includes/header.php';
?>

<div class="card p-4 shadow-sm border-0 mb-4">
    <form method="POST" action="enderecos.php">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="submit" class="btn btn-custom-action px-4">Salvar Endereço</button>
            <h2 class="h3 fw-bold mb-0 text-center flex-grow-1">Cadastro de Endereços</h2>
            <div style="width: 140px;"></div>
        </div>

        <?= $mensagem ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">CEP:</label>
                <input type="text" name="cep" class="form-control" placeholder="00000-000" required>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Tipo de Logradouro:</label>
                <input type="text" name="tipo_logradouro" class="form-control" placeholder="Rua, Avenida, Alameda...">
            </div>
            <div class="col-md-9">
                <label class="form-label fw-bold">Nome do Logradouro:</label>
                <input type="text" name="logradouro" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Número:</label>
                <input type="text" name="numero" class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Bairro:</label>
                <input type="text" name="bairro" class="form-control">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Município:</label>
                <input type="text" name="municipio" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Estado:</label>
                <input type="text" name="estado" maxlength="2" class="form-control" placeholder="PR" required>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($enderecos_lista)): ?>
<div class="card p-4 shadow-sm border-0">
    <h4 class="fw-bold mb-3">Endereços Cadastrados</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>CEP</th>
                    <th>Logradouro</th>
                    <th>Número</th>
                    <th>Bairro</th>
                    <th>Município/UF</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enderecos_lista as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['cep']) ?></td>
                    <td><?= htmlspecialchars($e['tipo_logradouro'] . ' ' . $e['logradouro']) ?></td>
                    <td><?= htmlspecialchars($e['numero']) ?></td>
                    <td><?= htmlspecialchars($e['bairro']) ?></td>
                    <td><?= htmlspecialchars($e['municipio'] . '/' . $e['estado']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
