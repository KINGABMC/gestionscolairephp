<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i>Gestion des Modules</h2>
        <a href="index.php?controller=module&action=form-module" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nouveau Module
        </a>
    </div>
    
    <!-- Formulaire de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php" method="get" class="row g-3">
                <input type="hidden" name="controller" value="module">
                <input type="hidden" name="action" value="list-modules">
                
                <div class="col-md-6">
                    <label for="nom" class="form-label">Rechercher par nom ou code</label>
                    <input type="text" class="form-control" name="nom" id="nom" 
                           placeholder="Nom ou code du module..." value="<?= $_REQUEST['nom'] ?? '' ?>">
                </div>
                
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des modules -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des Modules</h5>
        </div>
        <div class="card-body">
            <?php if(empty($modules)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun module trouvé</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom du module</th>
                                <th>Code</th>
                                <th>Coefficient</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modules as $module): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($module->getNom()) ?></strong>
                                    </td>
                                    <td>
                                        <code><?= htmlspecialchars($module->getCode()) ?></code>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= $module->getCoefficient() ?></span>
                                    </td>
                                    <td><?= $module->getDateCreation()->format('d/m/Y') ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" title="Voir professeurs">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>