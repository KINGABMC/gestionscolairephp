<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>Gestion des Demandes</h2>
    </div>
    
    <!-- Formulaire de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php" method="get" class="row g-3">
                <input type="hidden" name="controller" value="demande">
                <input type="hidden" name="action" value="list-demandes">
                
                <div class="col-md-4">
                    <label for="matricule" class="form-label">Rechercher par matricule</label>
                    <input type="text" class="form-control" name="matricule" id="matricule" 
                           placeholder="Matricule étudiant..." value="<?= $_REQUEST['matricule'] ?? '' ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="etat" class="form-label">Filtrer par état</label>
                    <select class="form-select" name="etat" id="etat">
                        <option value="">Tous les états</option>
                        <option value="EN_ATTENTE" <?= ($_REQUEST['etat'] ?? '') == 'EN_ATTENTE' ? 'selected' : '' ?>>En attente</option>
                        <option value="ACCEPTEE" <?= ($_REQUEST['etat'] ?? '') == 'ACCEPTEE' ? 'selected' : '' ?>>Acceptée</option>
                        <option value="REFUSEE" <?= ($_REQUEST['etat'] ?? '') == 'REFUSEE' ? 'selected' : '' ?>>Refusée</option>
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des demandes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des Demandes</h5>
        </div>
        <div class="card-body">
            <?php if(empty($demandes)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune demande trouvée</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Type</th>
                                <th>Motif</th>
                                <th>État</th>
                                <th>Date demande</th>
                                <th>Date traitement</th>
                                <?php if($_SESSION['user']->getRole() == 'RP'): ?>
                                <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($demandes as $demande): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($demande['matricule']) ?></small>
                                    </td>
                                    <td>
                                        <?php if($demande['type'] == 'SUSPENSION'): ?>
                                            <span class="badge bg-warning">Suspension</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Annulation</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span title="<?= htmlspecialchars($demande['motif']) ?>">
                                            <?= htmlspecialchars(substr($demande['motif'], 0, 50)) ?>...
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($demande['etat'] == 'EN_ATTENTE'): ?>
                                            <span class="badge bg-secondary">En attente</span>
                                        <?php elseif($demande['etat'] == 'ACCEPTEE'): ?>
                                            <span class="badge bg-success">Acceptée</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Refusée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($demande['date_demande'])) ?></td>
                                    <td>
                                        <?= $demande['date_traitement'] ? date('d/m/Y', strtotime($demande['date_traitement'])) : '-' ?>
                                    </td>
                                    <?php if($_SESSION['user']->getRole() == 'RP' && $demande['etat'] == 'EN_ATTENTE'): ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="controller" value="demande">
                                                <input type="hidden" name="action" value="traiter-demande">
                                                <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                                                <input type="hidden" name="decision" value="ACCEPTEE">
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Accepter cette demande ?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="controller" value="demande">
                                                <input type="hidden" name="action" value="traiter-demande">
                                                <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                                                <input type="hidden" name="decision" value="REFUSEE">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Refuser cette demande ?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>