<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chalkboard-teacher me-2"></i>Gestion des Professeurs</h2>
        <a href="index.php?controller=professeur&action=form-professeur" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nouveau Professeur
        </a>
    </div>
    
    <!-- Formulaire de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php" method="get" class="row g-3">
                <input type="hidden" name="controller" value="professeur">
                <input type="hidden" name="action" value="list-professeurs">
                
                <div class="col-md-6">
                    <label for="nom" class="form-label">Rechercher par nom</label>
                    <input type="text" class="form-control" name="nom" id="nom" 
                           placeholder="Nom ou prénom du professeur..." value="<?= $_REQUEST['nom'] ?? '' ?>">
                </div>
                
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des professeurs -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des Professeurs</h5>
        </div>
        <div class="card-body">
            <?php if(empty($professeurs)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun professeur trouvé</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Grade</th>
                                <th>Date d'ajout</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($professeurs as $professeur): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($professeur->getNomComplet()) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($professeur->getEmail()) ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= htmlspecialchars($professeur->getGrade()) ?></span>
                                    </td>
                                    <td><?= $professeur->getDateToString() ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" title="Voir modules">
                                                <i class="fas fa-book"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Affecter classe">
                                                <i class="fas fa-chalkboard"></i>
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