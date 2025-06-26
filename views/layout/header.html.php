<!doctype html>
<html lang="fr">
<head>
    <title>Gestion Scolaire ISM</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php?controller=dashboard">
                    <i class="fas fa-graduation-cap me-2"></i>ISM - Gestion Scolaire
                </a>
                
                <?php if(isset($_SESSION['user'])): ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>Tableau de bord
                            </a>
                        </li>
                        
                        <?php if($_SESSION['user']->getRole() == 'RP'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs me-1"></i>Administration
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?controller=classe&action=list-classe">
                                    <i class="fas fa-chalkboard me-1"></i>Classes
                                </a></li>
                                <li><a class="dropdown-item" href="index.php?controller=professeur&action=list-professeurs">
                                    <i class="fas fa-chalkboard-teacher me-1"></i>Professeurs
                                </a></li>
                                <li><a class="dropdown-item" href="index.php?controller=module&action=list-modules">
                                    <i class="fas fa-book me-1"></i>Modules
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?controller=demande&action=list-demandes">
                                    <i class="fas fa-clipboard-list me-1"></i>Demandes
                                </a></li>
                                <li><a class="dropdown-item" href="index.php?controller=dashboard&action=statistiques">
                                    <i class="fas fa-chart-bar me-1"></i>Statistiques
                                </a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if($_SESSION['user']->getRole() == 'ATTACHE'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-users me-1"></i>Étudiants
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?controller=etudiant&action=list-etudiant">
                                    <i class="fas fa-list me-1"></i>Liste des étudiants
                                </a></li>
                                <li><a class="dropdown-item" href="index.php?controller=inscription&action=form-inscription">
                                    <i class="fas fa-user-plus me-1"></i>Inscriptions
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?controller=demande&action=list-demandes">
                                    <i class="fas fa-clipboard-list me-1"></i>Demandes étudiants
                                </a></li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <?php if($_SESSION['user']->getRole() == 'PROFESSEUR'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=professeur&action=mes-classes">
                                <i class="fas fa-chalkboard me-1"></i>Mes Classes
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($_SESSION['user']->getRole() == 'ETUDIANT'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-clipboard-list me-1"></i>Mes Demandes
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?controller=demande&action=mes-demandes">
                                    <i class="fas fa-list me-1"></i>Consulter mes demandes
                                </a></li>
                                <li><a class="dropdown-item" href="index.php?controller=demande&action=form-demande">
                                    <i class="fas fa-plus me-1"></i>Nouvelle demande
                                </a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="navbar-nav">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= $_SESSION['user']->getNomComplet() ?>
                                <span class="badge bg-secondary ms-1"><?= $_SESSION['user']->getRole() ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="index.php?controller=auth&action=logout">
                                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>