<?php $title = 'Dashboard - Kaay Dem !'; ?>

<h1>👋 Bonjour, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur'); ?> !</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalTrips ?? 0; ?></div>
        <div class="stat-label">Mes trajets</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalActive ?? 0; ?></div>
        <div class="stat-label">Trajets actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">0</div>
        <div class="stat-label">Réservations</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">⭐ 0.0</div>
        <div class="stat-label">Note moyenne</div>
    </div>
</div>

<div class="card">
    <h3>📋 Actions rapides</h3>
    <div style="display:flex;gap:15px;flex-wrap:wrap;margin-top:15px;">
        <a href="/kaay_dem/trips" class="btn btn-primary">Voir les trajets</a>
        <?php if ($_SESSION['user_role'] == 'driver' || $_SESSION['user_role'] == 'both'): ?>
            <a href="/kaay_dem/trips/create" class="btn btn-success">Publier un trajet</a>
        <?php endif; ?>
    </div>
</div>

<?php if ($_SESSION['user_role'] == 'admin'): ?>
    <div class="card" style="background:#fff3e0;">
        <h3>🛠️ Administration</h3>
        <p>Vous êtes administrateur de la plateforme.</p>
    </div>
<?php endif; ?>