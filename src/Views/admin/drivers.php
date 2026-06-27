<?php $title = 'Gestion des conducteurs - Kaay Dem !'; ?>

<div class="card">
    <div class="card-header">
        <h2>👤 Gestion des conducteurs</h2>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($drivers) && count($drivers) > 0): ?>
                <?php foreach ($drivers as $driver): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($driver['first_name'] . ' ' . $driver['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($driver['email']); ?></td>
                        <td>
                            <?php if ($driver['is_driver_verified']): ?>
                                <span class="badge badge-success">✅ Vérifié</span>
                            <?php else: ?>
                                <span class="badge badge-pending">⏳ En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$driver['is_driver_verified']): ?>
                                <a href="/kaay_dem/admin/drivers/<?php echo $driver['id']; ?>/validate" class="btn btn-success btn-sm">Valider</a>
                            <?php else: ?>
                                <a href="/kaay_dem/admin/drivers/<?php echo $driver['id']; ?>/suspend" class="btn btn-danger btn-sm">Suspendre</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Aucun conducteur enregistré</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>