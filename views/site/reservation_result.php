<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/* @var $voyages array */
/* @var $idsString string */
/* @var $prixTotal float */
/* @var $nbPlaces int */
/* @var $user app\models\Internaute */

$nombreVoyages = count($voyages);
?>

<div class="container reservation-wrapper">
    <div class="card cericar-card shadow-sm">
        <div class="card-header cericar-header">
            <h4 class="m-0"><i class="fas fa-check-circle"></i> Confirmer la réservation</h4>
        </div>
        <div class="card-body p-4">

            <?php foreach ($voyages as $index => $voyage): ?>

                <div class="journey-segment">
                    <h5 class="journey-title">
                        <?php if($nombreVoyages > 1): ?>
                            <span class="segment-label">Trajet <?= $index + 1 ?> :</span>
                        <?php endif; ?>
                        
                        <?= Html::encode($voyage->trajetInfos->depart) ?> 
                        <i class="fas fa-arrow-right arrow-icon"></i> 
                        <?= Html::encode($voyage->trajetInfos->arrivee) ?>
                    </h5>
                    
                    <div class="journey-details">
                        <div class="detail-item">
                            <i class="fas fa-user-circle"></i> 
                            Conducteur : <strong><?= Html::encode($voyage->conducteurInfos->prenom) ?></strong>
                        </div>
                        <div class="detail-item price">
                             <?= $voyage->tarif ?> € <small>/ place</small>
                        </div>
                    </div>
                </div>

                <?php if ($index < $nombreVoyages - 1): ?>
                    <div class="connection-wrapper">
                        <div class="connection-line"></div>
                        <span class="badge connection-badge">
                            <i class="fas fa-exchange-alt"></i> Correspondance
                        </span>
                        <div class="connection-line"></div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
            
            <hr class="my-4">

            <div class="total-box">
                <span class="total-label">TOTAL À PAYER :</span>
                <div class="total-value">
                    <span class="amount"><?= number_format($prixTotal, 2) ?> €</span>
                    <span class="details">pour <?= $nbPlaces ?> place(s)</span>
                </div>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'reservation-form',
                'action' => ['site/reserver', 'ids' => $idsString, 'nb_places' => $nbPlaces] 
            ]); ?>

                <div class="mb-4">
                    <label class="form-label fw-bold">Réservé au nom de :</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" value="<?= Html::encode($user->prenom . ' ' . $user->nom) ?>" disabled>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-cericar btn-lg">
                        <i class="fas fa-check"></i> Confirmer et payer
                    </button>
                    
                    <a href="<?= Url::to(['site/search']) ?>" class="btn btn-outline-secondary ajax-link">
                        Annuler
                    </a>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>