<?php $title = 'Mes réservations - Kaay Dem !'; ?>

<div class="card">
    <div class="card-header">
        <h2>📋 Réservations reçues</h2>
    </div>

    <?php if (isset($reservations) && count($reservations) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Trajet</th>
                    <th>Passager</th>
                    <th>Places</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($reservation['departure'] ?? '') . ' → ' . htmlspecialchars($reservation['arrival'] ?? ''); ?>
                        </td>
                        <td><?php echo htmlspecialchars($reservation['passenger_name'] ?? 'Inconnu'); ?></td>
                        <td><?php echo $reservation['seats']; ?></td>
                        <td>
                            <?php if ($reservation['status'] == 'pending'): ?>
                                <span class="badge badge-pending">⏳ En attente</span>
                            <?php elseif ($reservation['status'] == 'confirmed'): ?>
                                <span class="badge badge-confirmed">✅ Confirmée</span>
                            <?php elseif ($reservation['status'] == 'completed'): ?>
                                <span class="badge badge-completed">✅ Terminée</span>
                            <?php else: ?>
                                <span class="badge badge-cancelled">❌ Annulée</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($reservation['status'] == 'pending'): ?>
                                <a href="/kaay_dem/reservations/<?php echo $reservation['id']; ?>/accept" class="btn btn-success btn-sm">Accepter</a>
                                <a href="/kaay_dem/reservations/<?php echo $reservation['id']; ?>/reject" class="btn btn-danger btn-sm">Refuser</a>
                            <?php elseif ($reservation['status'] == 'confirmed'): ?>
                                <span class="badge badge-confirmed">✅ Confirmée</span>
                            <?php elseif ($reservation['status'] == 'completed'): ?>
                                <span class="badge badge-completed">✅ Terminée</span>
                            <?php else: ?>
                                <span class="badge badge-cancelled">❌ Annulée</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;padding:20px;">Aucune réservation reçue pour le moment.</p>
    <?php endif; ?>
</div>