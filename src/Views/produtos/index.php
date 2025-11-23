<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-box mr-2"></i> Produtos Cadastrados
        </h3>
        <a href="?controller=Produto&action=create" class="btn btn-danger btn-sm">
            <i class="fas fa-plus"></i> Novo Produto
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($produtos)): ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Nenhum produto cadastrado ainda</h4>
                <p class="text-muted">Clique em "Novo Produto" para começar!</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($produtos as $prod): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <!-- Imagem do produto -->
                            <?php if (!empty($prod['prod_imagem']) && $prod['prod_imagem'] !== 'sem-foto.jpg'): ?>
                                <img src="public/assets/produtos/<?= htmlspecialchars($prod['prod_imagem']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($prod['prod_nome']) ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font-weight-bold">
                                    <?= htmlspecialchars($prod['prod_nome']) ?>
                                </h5>
                                
                                <?php if (!empty($prod['categoria_nome'])): ?>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-tag"></i> <?= htmlspecialchars($prod['categoria_nome']) ?>
                                    </p>
                                <?php endif; ?>

                                <p class="card-text mt-auto">
                                    <span class="h4 text-danger font-weight-bold">
                                        R$ <?= number_format($prod['prod_preco'], 2, ',', '.') ?>
                                    </span>
                                </p>

                                <div class="mt-3">
                                    <a href="?controller=Produto&action=edit&id=<?= $prod['prod_codigo'] ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="?controller=Produto&action=delete&id=<?= $prod['prod_codigo'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>