<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $voyages app\models\Voyage[] */

$this->title = 'Mes offres de voyages';
?>

<div id="mes-offres-view" class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-car-side text-success me-2"></i> Mes trajets publiés</h2>
        
       
    </div>

    <?php if (empty($voyages)): ?>
        <div class="alert alert-secondary border-0 shadow-sm text-center py-5">
            <div class="mb-3">
                <i class="fas fa-road fa-3x text-muted"></i>
            </div>
            <h4>Vous n'avez aucun trajet en cours.</h4>
            <p class="text-muted">Vous avez une place libre dans votre voiture ? Proposez-la !</p>
        </div>

    <?php else: ?>
        <div class="row">
            <?php foreach ($voyages as $voyage): ?>
                <div class="col-lg-12 mb-4">
                    <div class="card shadow-sm border-0">
                        
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="badge bg-success me-2" style="font-size: 0.9rem;">
                                    <i class="far fa-clock"></i> <?= Html::encode($voyage->heuredepart) ?>h00
                                </span>
                                
                                <span class="fw-bold fs-5">
                                    <?php if ($voyage->trajetInfos): ?>
                                        <?= Html::encode($voyage->trajetInfos->depart) ?> 
                                        <i class="fas fa-long-arrow-alt-right mx-2 text-secondary"></i> 
                                        <?= Html::encode($voyage->trajetInfos->arrivee) ?>
                                    <?php else: ?>
                                        <span class="text-danger">Trajet #<?= $voyage->trajet ?> (Données introuvables)</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <div>
                                <?php $nbResa = count($voyage->reservations); ?>
                                <span class="badge <?= $nbResa > 0 ? 'bg-light text-success' : 'bg-secondary text-light' ?> rounded-pill px-3">
                                    <?= $nbResa ?> réservation<?= $nbResa > 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-body bg-light">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">
                                <i class="fas fa-users me-1"></i> 
                            </h6>

                            <?php if ($nbResa > 0): ?>
                                <ul class="list-group border-0">
                                    <?php foreach ($voyage->reservations as $resa): ?>
                                        <li class="list-group-item border-0 shadow-sm mb-2 d-flex justify-content-between align-items-center rounded bg-white">
                                            
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center me-3 fw-bold" 
                                                     style="width:40px; height:40px;">
                                                    <?php 
                                                        // On utilise la relation définie dans Reservation.php
                                                        $passager = $resa->voyageurInfos; 
                                                        echo $passager ? strtoupper(substr($passager->prenom, 0, 1)) : '?';
                                                    ?>
                                                </div>
                                                
                                                <div>
                                                    <?php if ($passager): ?>
                                                        <div class="fw-bold text-dark">
                                                            <?= Html::encode($passager->prenom . ' ' . $passager->nom) ?>
                                                        </div>
                                                        <div class="small text-muted">Membre CERICar</div>
                                                    <?php else: ?>
                                                        <span class="text-danger fst-italic">Utilisateur inconnu (ID: <?= $resa->voyageur ?>)</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <span class="badge bg-success bg-opacity-75 text-white">
                                                <?= $resa->nbplaceresa ?> place(s)
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                        
                            <?php endif; ?>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between text-muted small py-2">
                            <span>
                                <i class="fas fa-chair me-1"></i> Places restantes : 
                                <strong><?= $voyage->getPlacesRestantes() ?></strong>
                            </span>
                            <span>
                                <i class="fas fa-tag me-1"></i> Tarif : 
                                <strong><?= Html::encode($voyage->tarif) ?> €</strong> / pers
                            </span>
                            <span>
                                <i class="fas fa-tag me-1"></i> contrainte : 
                                <strong><?= Html::encode($voyage->contraintes) ?> </strong> 
                            </span>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>