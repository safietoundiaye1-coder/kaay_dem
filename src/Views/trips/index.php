<?php $title = 'Kaay Dem ! - Covoiturage étudiant'; ?>

<div class="hero">
    <h1>
    <img src="/kaay_dem/assets/images/logo.png" alt="Kaay Dem !" style="height:60px; vertical-align:middle;">
    <span>Kaay</span> Dem !
</h1>
    <p>La plateforme de covoiturage réservée à la communauté étudiante</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="/kaay_dem/register" class="btn btn-success">Rejoindre</a>
        <a href="/kaay_dem/login" class="btn btn-outline" style="border-color:white;color:white;">Se connecter</a>
    <?php else: ?>
        <a href="/kaay_dem/trips/create" class="btn btn-success">Publier un trajet</a>
        <a href="/kaay_dem/trips" class="btn btn-outline" style="border-color:white;color:white;">Voir les trajets</a>
    <?php endif; ?>
</div>

<div class="card">
    <h3>🔍 Rechercher un trajet</h3>
    <form method="GET" action="/kaay_dem/trips" style="margin-top:15px;">
        <div class="form-row">
            <div class="form-group">
                <label>Départ</label>
                <input type="text" name="departure" placeholder="Dakar" value="<?php echo $_GET['departure'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Arrivée</label>
                <input type="text" name="arrival" placeholder="Diamniadio" value="<?php echo $_GET['arrival'] ?? ''; ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <a href="/kaay_dem/trips" class="btn btn-outline">Réinitialiser</a>
    </form>
</div>

<h2>🚗 Trajets disponibles</h2>
<div class="trips-grid">
    <?php if (isset($trips) && count($trips) > 0): ?>
        <?php foreach ($trips as $trip): ?>
            <div class="trip-card">
                <div class="trip-header">
                    <span class="trip-route">
                        <?php echo htmlspecialchars($trip->getDepartureCity()); ?> 
                        → <?php echo htmlspecialchars($trip->getArrivalCity()); ?>
                    </span>
                    <span class="trip-price"><?php echo number_format($trip->getPricePerSeat(), 0); ?> F</span>
                </div>
                <div class="trip-info">
                    <span>📅 <?php echo $trip->getDepartureTime()->format('d/m/Y H:i'); ?></span>
                    <span>💺 <?php echo $trip->getAvailableSeats(); ?> places</span>
                </div>
                <div class="trip-footer">
                    <a href="/kaay_dem/trips/<?php echo $trip->getId(); ?>" class="btn btn-primary btn-sm">Voir</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card" style="text-align:center;padding:40px;grid-column:1/-1;">
            <p>Aucun trajet disponible pour le moment</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/kaay_dem/trips/create" class="btn btn-primary">Publier un trajet</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>