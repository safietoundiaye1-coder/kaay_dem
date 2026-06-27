<?php $title = 'Gestion des signalements - Kaay Dem !'; ?>

<div class="card">
    <div class="card-header">
        <h2>🚨 Signalements</h2>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Signalé par</th>
                <th>Utilisateur signalé</th>
                <th>Motif</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($reports) && count($reports) > 0): ?>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['reporter_name'] ?? 'Inconnu'); ?></td>
                        <td><?php echo htmlspecialchars($report['reported_name'] ?? 'Inconnu'); ?></td>
                        <td><?php echo htmlspecialchars($report['reason']); ?></td>
                        <td>
                            <?php if ($report['status'] == 'pending'): ?>
                                <span class="badge badge-pending">⏳ En attente</span>
                            <?php elseif ($report['status'] == 'resolved'): ?>
                                <span class="badge badge-success">✅ Résolu</span>
                            <?php else: ?>
                                <span class="badge badge-cancelled">❌ Rejeté</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($report['status'] == 'pending'): ?>
                                <a href="/kaay_dem/admin/reports/<?php echo $report['id']; ?>/resolve" class="btn btn-success btn-sm">Résoudre</a>
                                <a href="/kaay_dem/admin/reports/<?php echo $report['id']; ?>/dismiss" class="btn btn-danger btn-sm">Rejeter</a>
                            <?php else: ?>
                                <span class="badge badge-completed">✅ Traité</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Aucun signalement</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>