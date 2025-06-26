<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Ajouter un nouveau professeur
                    </h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="controller" value="professeur">
                        <input type="hidden" name="action" value="save-professeur">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control" name="nom" id="nom" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" name="prenom" id="prenom" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade *</label>
                            <select class="form-select" name="grade" id="grade" required>
                                <option value="">Sélectionner un grade</option>
                                <option value="Assistant">Assistant</option>
                                <option value="Maître Assistant">Maître Assistant</option>
                                <option value="Maître de Conférences">Maître de Conférences</option>
                                <option value="Professeur">Professeur</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Modules enseignés</label>
                            <div class="row">
                                <?php foreach ($modules as $module): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="modules[]" 
                                                   value="<?= $module->getId() ?>" id="module_<?= $module->getId() ?>">
                                            <label class="form-check-label" for="module_<?= $module->getId() ?>">
                                                <?= htmlspecialchars($module->getNom()) ?> (<?= htmlspecialchars($module->getCode()) ?>)
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=professeur&action=list-professeurs" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Enregistrer le professeur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>