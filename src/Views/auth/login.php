<?php $title = 'Connexion - Kaay Dem !'; ?>

<div class="auth-page">
    <div class="card auth-card">
        <div class="logo">
            <h2>🚗 <span>Kaay</span> Dem !</h2>
            <p>Connectez-vous à votre compte</p>
        </div>

        <form method="POST" action="/kaay_dem/login">
            <div class="form-group">
                <label>📧 Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>🔒 Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>

        <p style="text-align:center;margin-top:20px;">
            Pas de compte ? <a href="/kaay_dem/register">S'inscrire</a>
        </p>
    </div>
</div>