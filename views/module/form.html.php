<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Créer un nouveau module
                    </h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="controller" value="module">
                        <input type="hidden" name="action" value="save-module">
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du module *</label>
                            <input type="text" class="form-control" name="nom" id="nom" 
                                   placeholder="Ex: Programmation Web" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Code du module *</label>
                            <input type="text" class="form-control" name="code" id="code" 
                                   placeholder="Ex: PROG_WEB" required>
                            <div class="form-text">Code unique pour identifier le module</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="coefficient" class="form-label">Coefficient *</label>
                            <select class="form-select" name="coefficient" id="coefficient" required>
                                <option value="">Sélectionner le coefficient</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=module&action=list-modules" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Créer le module
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>