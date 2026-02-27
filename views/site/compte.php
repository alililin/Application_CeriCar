<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Mon Compte';
?>

<div class="account-container">
    
    <h3 class="mb-3">Coordonnées perso</h3>

    <div class="card p-3 mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="user-avatar-circle">
                    <?= strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) ?>
                </div>
                <div>
                    <h5 class="m-0 fw-bold"><?= Html::encode($user->prenom . ' ' . $user->nom) ?></h5>
                    <small class="text-muted">Membre CERICar</small> 
                </div>
            </div>
            </div>
    </div>

    <div class="card p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-medium">Votre adresse e-mail</div>
                <small class="text-muted"><?= Html::encode($user->email ?? $user->mail ?? 'Non renseigné') ?></small>
            </div>
            <i class="fas fa-pen text-muted" style="font-size: 0.8rem; cursor: pointer;"></i>
        </div>
    </div>

    <h3 class="mb-3 mt-4">Sécurité & Paramètres</h3>

    <a href="#" class="text-decoration-none text-dark">
        <div class="card p-3 mb-3 card-hover-effect">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-medium">Modifier votre mot de passe</div>
                    <small class="text-muted">••••••••</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        </div>
    </a>

    <a href="#" class="text-decoration-none text-dark">
        <div class="card p-3 mb-5 card-hover-effect">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-medium">Paramètres</div>
                    <small class="text-muted">Langue, Notifications, Confidentialité</small>
                </div>
                <i class="fas fa-chevron-right text-muted"></i>
            </div>
        </div>
    </a>

    <div class="text-center pb-4">
   <div class="text-center pb-4">
    <a href="<?= Url::to(['site/logout']) ?>" 
       class="btn btn-danger btn-logout px-5 rounded-pill shadow-sm"
       method="post"> Se déconnecter
    </a>
</div>

</div>