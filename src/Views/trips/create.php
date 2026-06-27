<?php $title = 'Publier un trajet - Kaay Dem !'; ?>

<div class="container">
    <h1>🚗 Publier un nouveau trajet</h1>

    <div class="card">
        <form method="POST" action="/kaay_dem/trips">
            <div class="form-row">
                <div class="form-group">
                    <label>📍 Ville de départ *</label>
                    <input type="text" name="departure_city" placeholder="Ex: Dakar" required>
                </div>
                <div class="form-group">
                    <label>📍 Ville d'arrivée *</label>
                    <input type="text" name="arrival_city" placeholder="Ex: Diamniadio" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>📅 Date et heure de départ *</label>
                <input type="datetime-local" name="departure_time" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>💺 Nombre de places *</label>
                    <input type="number" name="available_seats" min="1" max="8" value="3" required>
                </div>
                <div class="form-group">
                    <label>💰 Prix par place (FCFA) *</label>
                    <input type="number" name="price_per_seat" min="0" value="1000" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>📍 Points d'arrêt (optionnel)</label>
                <input type="text" name="stop_points" placeholder="Ex: Rufisque, Bargny (séparés par des virgules)">
                <small style="color: #666;">Séparez les points par des virgules</small>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Publier le trajet</button>
                <a href="/kaay_dem/trips" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>