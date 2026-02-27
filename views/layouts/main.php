<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
$this->registerCsrfMetaTags(); 
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?php 
    $this->registerJsFile('@web/js/app.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    ?>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        
        <a class="navbar-brand ajax-link" href="<?= Url::to(['site/search']) ?>">CERICar.</a>
        
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav align-items-center" id="navbar-menu">
                
                <li class="nav-item">
                    <a class="nav-link ajax-link" href="<?= Url::to(['site/search']) ?>">Trouver un trajet</a>
                </li>

                <?php if (Yii::$app->user->isGuest): ?>
                    
                    <li class="nav-item">
                        <a class="nav-link ajax-link" href="<?= Url::to(['site/proposer']) ?>">Proposer un trajet</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link ajax-link" href="<?= Url::to(['site/signup']) ?>">Compte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ajax-link" href="<?= Url::to(['site/login']) ?>">Se connecter</a>
                    </li>
                    
                <?php else: ?>
                    <?php $user = Yii::$app->user->identity;
                        $isDriver = !empty($user->permis); 
                    ?>
                    <li class="nav-item">
                        <a class="nav-link ajax-link" href="<?= Url::to(['site/billets']) ?>">Billets</a>
                    </li>

                    <?php if ($isDriver): ?>
                        <li class="nav-item">
                            <a class="nav-link ajax-link" href="<?= Url::to(['site/mesoffres']) ?>">Mes Offres</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ajax-link" href="<?= Url::to(['site/proposer']) ?>">Proposer un trajet</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link ajax-link user-profile-link" id="header-pseudo" href="<?= Url::to(['site/compte']) ?>" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <?php 
                                    echo strtoupper(substr($user->prenom,0,1).substr($user->nom,0,1)); 
                                ?>
                            </div>
                            <span class="user-name"><?= Html::encode($user->prenom) ?></span>
                        </a>
                        </li>
                    
                   

                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div id="notification-banner"></div>

<main id="content-area">
    <?= $content ?> 
</main>

<?php $this->endBody() ?>
<footer class="custom-footer">
    <div class="footer-container">
        <div class="footer-left">
            <h2 class="footer-logo">CERICar</h2>
            <p class="footer-text">
                Trouvez votre trajet au meilleur prix.  
                Covoiturage simple, rapide et fiable pour tous vos déplacements.
            </p>
            <div class="footer-socials">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="footer-right">
            <h3 class="footer-title">Contact</h3>
            <p><i class="fas fa-envelope"></i> contact@cericar.com</p>
            <p><i class="fas fa-phone"></i> +334 66 25 45 44</p>
        </div>
    </div>
    <div class="footer-bottom">
     © 2025 CERICar — Made by Lina ALILI
    </div>
</footer>
</body>
</html>
<?php $this->endPage() ?>