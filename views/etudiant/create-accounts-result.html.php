<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-cog me-2"></i>Création des comptes étudiants
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white text-center">
                                <div class="card-body">
                                    <h3><?= $results['created'] ?></h3>
                                    <p class="mb-0">Comptes créés</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white text-center">
                                <div class="card-body">
                                    <h3><?= $results['skipped'] ?></h3>
                                    <p class="mb-0">Comptes existants</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white text-center">
                                <div class="card-body">
                                    <h3><?= count($results['errors']) ?></h3>
                                    <p class="mb-0">Erreurs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($results['created'] > 0): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong><?= $results['created'] ?> compte(s) utilisateur(s) créé(s) avec succès !</strong>
                            <br><small>Les étudiants peuvent maintenant se connecter avec leur matricule@ism.sn et le mot de passe "etudiant123"</small>
                        </div>
                    <?php endif; ?>

                    <?php if($results['skipped'] > 0): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong><?= $results['skipped'] ?> étudiant(s) avaient déjà un compte utilisateur.</strong>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($results['errors'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreurs rencontrées :</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach($results['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-warning">
                        <i class="fas fa-key me-2"></i>
                        <strong>Informations de connexion pour les étudiants :</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Email :</strong> [matricule]@ism.sn (ex: ism2024001@ism.sn)</li>
                            <li><strong>Mot de passe :</strong> etudiant123</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=etudiant&action=list-etudiant" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                        </a>
                        <a href="index.php?controller=dashboard" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-1"></i>Tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>