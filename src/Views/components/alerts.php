<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['sucesso'] ?> <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['erro'] ?> <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>