<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $reservations app\models\Reservation[] */

$this->title = 'Mes Billets';
?>

<div class="container mt-5">
    <h2 class="mb-4"> Mes Billets & Réservations</h2>

    <?php if (empty($reservations)): ?>
        <div class="alert alert-warning">
            Vous n'avez pas encore de billets. 
            <a href="<?= Url::to(['site/search']) ?>" class="alert-link ajax-link">Rechercher un trajet</a>.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reservations as $res): ?>
                <?php 
                    $v = $res->voyageInfos; 
                    if (!$v || !$v->trajetInfos) continue;
                    $prixBillet = $v->tarif * $v->trajetInfos->distance * $res->nbplaceresa;
                ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-start border-success border-4">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                <i></i> Billet N° <?= $res->id ?>
                            </h5>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?= Html::encode($v->trajetInfos->depart) ?></h4>
                                    <small class="text-muted">Départ</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="mb-0"><?= Html::encode($v->trajetInfos->arrivee) ?></h4>
                                    <small class="text-muted">Arrivée</small>
                                </div>
                            </div>
                            
                            <div class="mt-3 p-2 bg-light rounded">
                                
                                <p class="mb-1">
                                    <i class="fas fa-user-circle"></i> 
                                    <strong>Conducteur :</strong> <?= Html::encode($v->conducteurInfos->prenom) ?>
                                </p>
                                <p class="mb-1">
                                    <i></i> 
                                    <strong>Contraintes : </strong> <?= Html::encode($v->contraintes) ?>
                                </p>
                                
                                <p class="mb-1">
                                    <i class="fas fa-users"></i> 
                                    <strong>Places réservées :</strong> <?= $res->nbplaceresa ?>
                                </p>

                                <p class="mb-0 text-success fw-bold">
                                    <i class="fas fa-euro-sign"></i> 
                                    <strong>Prix Total :</strong> <?= number_format($prixBillet, 2) ?> €
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>