<?php
include 'includes/header.php';
?>
<div class="card p-5 shadow-sm text-center border-0">
    <h1 class="display-5 fw-bold text-dark mb-3">Sistema de Gestão Logística</h1>
    <p class="lead text-muted mb-4">Projeto Prático EAD - Fundamentos de Programação para Internet (UNIPAR)</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
        <a href="produtos.php" class="btn btn-custom-action btn-lg px-4">Cadastrar Produtos</a>
        <a href="enderecos.php" class="btn btn-custom-action btn-lg px-4">Cadastrar Endereços</a>
        <a href="transportadores.php" class="btn btn-custom-action btn-lg px-4">Cadastrar Transportadoras</a>
        <a href="fretes.php" class="btn btn-primary btn-lg px-4">Gerar Frete</a>
        <a href="relatorio.php" class="btn btn-secondary btn-lg px-4">Relatórios</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
