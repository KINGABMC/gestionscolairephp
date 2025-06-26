<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Nouvelle demande
                    </h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="controller" value="demande">
                        <input type="hidden" name="action" value="save-demande">
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de demande *</label>
                            <select class="form-select" name="type" id="type" required>
                                <option value="">Sélectionner le type</option>
                                <option value="SUSPENSION">Suspension d'inscription</option>
                                <option value="ANNULATION">Annulation d'inscription</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="motif" class="form-label">Motif de la demande *</label>
                            <textarea class="form-control" name="motif" id="motif" rows="4" 
                                      placeholder="Expliquez les raisons de votre demande..." required></textarea>
                            <div class="form-text">Veuillez détailler les raisons de votre demande</div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Information :</strong> Votre demande sera examinée par le Responsable Pédagogique. 
                            Vous recevrez une réponse dans les plus brefs délais.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=demande&action=mes-demandes" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Envoyer la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>