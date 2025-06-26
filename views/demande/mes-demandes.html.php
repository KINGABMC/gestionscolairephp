<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>Mes Demandes</h2>
        <a href="index.php?controller=demande&action=form-demande" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nouvelle Demande
        </a>
    </div>
    
    <!-- Formulaire de filtrage -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php" method="get" class="row g-3">
                <input type="hidden" name="controller" value="demande">
                <input type="hidden" name="action" value="mes-demandes">
                
                <div class="col-md-6">
                    <label for="etat" class="form-label">Filtrer par état</label>
                    <select class="form-select" name="etat" id="etat">
                        <option value="">Tous les états</option>
                        <option value="EN_ATTENTE" <?= ($_REQUEST['etat'] ?? '') == 'EN_ATTENTE' ? 'selected' : '' ?>>En attente</option>
                        <option value="ACCEPTEE" <?= ($_REQUEST['etat'] ?? '') == 'ACCEPTEE' ? 'selected' : '' ?>>Acceptée</option>
                        <option value="REFUSEE" <?= ($_REQUEST['etat'] ?? '') == 'REFUSEE' ? 'selected' : '' ?>>Refusée</option>
                    </select>
                </div>
                
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des demandes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Historique de mes demandes</h5>
        </div>
        <div class="card-body">
            <?php if(empty($demandes)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Vous n'avez encore formulé aucune demande</p>
                    <a href="index.php?controller=demande&action=form-demande" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Créer ma première demande
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($demandes as $demande): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>
                                        <?php if($demande->getType() == 'SUSPENSION'): ?>
                                            <i class="fas fa-pause text-warning me-1"></i>Suspension
                                        <?php else: ?>
                                            <i class="fas fa-times text-danger me-1"></i>Annulation
                                        <?php endif; ?>
                                    </span>
                                    <span>
                                        <?php if($demande->getEtat() == 'EN_ATTENTE'): ?>
                                            <span class="badge bg-secondary">En attente</span>
                                        <?php elseif($demande->getEtat() == 'ACCEPTEE'): ?>
                                            <span class="badge bg-success">Acceptée</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Refusée</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars($demande->getMotif()) ?></p>
                                    <div class="row text-muted small">
                                        <div class="col-6">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= $demande->getDateDemandeToString() ?>
                                        </div>
                                        <?php if($demande->getDateTraitement()): ?>
                                        <div class="col-6">
                                            <i class="fas fa-check me-1"></i>
                                            <?= $demande->getDateTraitementToString() ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>