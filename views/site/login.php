<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Connexion';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Connexion</h2>

       
      
     <?= Html::beginForm(['site/login'], 'post', [
    'id' => 'login-form',
    'options' => ['autocomplete' => 'off'] // Empêche l'affichage de la petite fenêtre
]); ?>

            <div class="form-group-modern">
                <?= Html::activeTextInput($model, 'username', ['class' => 'input-modern', 'autocomplete' => 'off', 'placeholder' => 'Pseudo', ]) ?>
                <div class="invalid-feedback" style="display:block">
                    <?= Html::error($model, 'username') ?>
                </div>
            </div>

            <div class="form-group-modern">
                <?= Html::activePasswordInput($model, 'password', [
                    'class' => 'input-modern', 'autocomplete' => 'off',
                    'placeholder' => 'Mot de passe'
                ]) ?>
                <div class="invalid-feedback" style="display:block">
                    <?= Html::error($model, 'password') ?>
                </div>
            </div>

            <div class="remember-me-container mb-3">
                <?= Html::activeCheckbox($model, 'rememberMe', ['label' => 'Se souvenir de moi']) ?>
            </div>
            
            <?= Html::submitButton('Se connecter', ['class' => 'btn-auth w-100']) ?>

        <?= Html::endForm() ?>
        
        <div class="auth-footer mt-3">
            Pas de compte ? <a href="<?= Url::to(['site/signup']) ?>" class="ajax-link">Créer un compte</a>
        </div>
    </div>
</div>