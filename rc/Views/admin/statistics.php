<?php $title = 'Statistiques - Kaay Dem !'; ?>

<h1>📊 Statistiques</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalUsers ?? 0; ?></div>
        <div class="stat-label">Utilisateurs</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalTrips ?? 0; ?></div>
        <div class="stat-label">Trajets</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalReservations ?? 0; ?></div>
        <div class="stat-label">Réservations</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $occupancyRate ?? 0; ?>%</div>
        <div class="stat-label">Taux d'occupation</div>
    </div>
</div>

<div class="card">
    <h3>🏆 Top conducteurs</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Conducteur</th>
                <th>Nombre de trajets</th>
                <th>Note moyenne</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($topDrivers) && count($topDrivers) > 0): ?>
                <?php foreach ($topDrivers as $driver): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($driver['first_name'] . ' ' . $driver['last_name']); ?></td>
                        <td><?php echo $driver['trip_count']; ?></td>
                        <td>⭐ <?php echo number_format($driver['avg_rating'] ?? 0, 1); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align:center;">Aucun conducteur</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
