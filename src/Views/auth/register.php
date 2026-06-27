<?php $title = 'Inscription - Kaay Dem !'; ?>

<div class="auth-page">
    <div class="card auth-card">
        <div class="logo">
            <h2>🚗 <span>Kaay</span> Dem !</h2>
            <p>Rejoignez la communauté étudiante</p>
        </div>

        <form method="POST" action="/kaay_dem/register">
            <div class="form-row">
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>
            <div class="form-group">
                <label>📧 Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>🔒 Mot de passe</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" required minlength="6">
            </div>
            <div class="form-group">
                <label>Je suis :</label>
                <select name="role">
                    <option value="passenger">Passager</option>
                    <option value="driver">Conducteur</option>
                    <option value="both">Conducteur et Passager</option>
                </select>
            </div>
            <div class="form-group">
                <label>Numéro étudiant (optionnel)</label>
                <input type="text" name="student_id">
            </div>
            <button type="submit" class="btn btn-success btn-block">S'inscrire</button>
        </form>

        <p style="text-align:center;margin-top:20px;">
            Déjà inscrit ? <a href="/kaay_dem/login">Se connecter</a>
        </p>
    </div>
</div>