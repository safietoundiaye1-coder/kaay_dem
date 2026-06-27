<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Kaay Dem !'; ?></title>
    <link rel="stylesheet" href="/kaay_dem/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/kaay_dem" class="navbar-brand">
                <img src="/kaay_dem/assets/images/logo.png" alt="Kaay Dem !">
                <div class="brand-text">
                    <span class="kaay">Kaay</span>
                    <span class="dem">Dem</span>
                    <span class="exclamation">!</span>
                </div>
            </a>
            <ul class="navbar-nav">
                <li><a href="/kaay_dem">Accueil</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/kaay_dem/dashboard">Dashboard</a></li>
                    <li><a href="/kaay_dem/logout" class="btn-logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/kaay_dem/login">Connexion</a></li>
                    <li><a href="/kaay_dem/register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?>">
                <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>
        <?php echo $content ?? ''; ?>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <a href="/kaay_dem">Kaay Dem !</a> - Covoiturage étudiant</p>
        </div>
    </footer>
</body>
</html>