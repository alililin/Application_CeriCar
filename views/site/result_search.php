<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $resultats array */
/* @var $nbPersonnes int */
?>

<?php if (isset($resultats) && !empty($resultats)) : ?>
    
    <div class="results-container">
        <?php foreach ($resultats as $item): ?>

            <?php 
                $v = $item['voyageObject']; 
                $idsResa = $item['ids_reservation']; 
                $details = $item['detailsTrajet']; 
                $borderClass = $item['borderClass']; 
                
                // On récupère l'info calculée par le contrôleur
                $estComplet = isset($item['estComplet']) ? $item['estComplet'] : ($item['places'] < $nbPersonnes);
                
                // On ajoute une classe 'card-disabled' si complet pour griser légèrement
                $statusClass = $estComplet ? 'card-disabled opacity-75' : '';
            ?>

            <div class="trip-card <?= $statusClass ?> <?= $borderClass ?>" style="position: relative;">
                
                <div class="trip-info">
                    
                    <div class="trip-time">
                        <span class="departure-time"><?= $item['heureDepart'] ?></span>
                        
                        <div class="trip-duration-line">
                            <span class="dot-start"></span>
                            <span class="line"></span>
                            <span class="duration-text"><?= $item['distanceTotal'] ?> km</span>
                            <span class="dot-end"></span>
                        </div>
                        
                        <span class="arrival-time "><?= $item['heureArrivee'] ?> </span>
                    </div>

                    <div class="trip-route">
                        <?php foreach ($details as $index => $etape): ?>
                            <h4 class="route-cities" style="<?= $index > 0 ? 'margin-top: 10px; font-size: 0.9em;' : '' ?>">
                                <?= Html::encode($etape['depart']) ?> 
                                <i class="fas fa-long-arrow-alt-right text-primary mx-2"></i> 
                                <?= Html::encode($etape['arrivee']) ?>
                                <span >
                                    <?= $etape['infos'] ?>
                                </span>
                            </h4>
                        <?php endforeach; ?>
                        
                        <div class="driver-details">
                            <div class="driver-badge">
                                <i class="fas fa-user-circle"></i> 
                                <span><?= Html::encode($v->conducteurInfos->pseudo) ?></span>
                            </div>
                            <div class="car-badge">
                                <i class="fas fa-car"></i>
                                <span><?= Html::encode($v->marqueVehicule->marquev) ?> - <?= Html::encode($v->typeVehicule->typev) ?></span>
                            </div>
                            <?php if(!empty($v->contraintes)): ?>
                                <div class="info-badge" title="<?= Html::encode($v->contraintes) ?>" style="color: orange;">
                                    <i class="fas fa-exclamation-circle"></i> Info
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="trip-action">
                    <div class="trip-price">
                        <?= number_format($item['coutTotal'], 2) ?> €
                    </div>
                    
                    <div class="trip-seats">
                        <?php if ($estComplet): ?>
                            <span class="badge bg-secondary">Complet</span>
                        <?php else: ?>
                            <span class="badge-status available"><?= $item['places'] ?> place(s)</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!$estComplet): ?>
                        <a href="<?= Url::to(['site/reserver', 
                            'ids' => $idsResa, 
                            'nb_places' => $nbPersonnes
                        ]) ?>" class="btn-book ajax-link">
                            Réserver
                        </a>
                    <?php endif; ?>
                </div>

            </div>

        <?php endforeach; ?>
    </div>

<?php endif; ?>