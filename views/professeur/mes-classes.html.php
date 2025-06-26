<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chalkboard me-2"></i>Mes Classes</h2>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Classes assignées</h5>
        </div>
        <div class="card-body">
            <?php if(empty($classes)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune classe assignée</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($classes as $classe): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><?= htmlspecialchars($classe['libelle']) ?></h6>
                                    <span class="badge bg-primary"><?= htmlspecialchars($classe['filiere']) ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Module :</strong> <?= htmlspecialchars($classe['module_nom']) ?><br>
                                        <strong>Code :</strong> <?= htmlspecialchars($classe['module_code']) ?><br>
                                        <strong>Niveau :</strong> <?= htmlspecialchars($classe['niveau']) ?>
                                    </p>
                                    <a href="index.php?controller=inscription&action=list-etudiants-classe&classe_id=<?= $classe['id'] ?>&annee_scolaire=2024-2025" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-users me-1"></i>Voir les étudiants
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>