<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Voyage */
/* @var $typesVehicules array */
/* @var $marquesVehicules array */
/* @var $villeDepart string */
/* @var $villeArrivee string */

$this->title = 'Proposer un trajet';
?>

<div class="site-proposer container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-car me-2"></i>Proposer un voyage</h4>
                </div>
                
                <div class="card-body p-4">

                    <?php $form = ActiveForm::begin([
                        'id' => 'form-proposer', 
                        'action' => ['site/proposer'],
                        'options' => [
                            'class' => 'needs-validation',
                            'novalidate' => true 
                        ],
                        'enableClientValidation' => false, // on Désactive la validation JS de Yii
                        'enableAjaxValidation' => false,
                        
                  
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{hint}", 
                            'errorOptions' => ['class' => 'd-none'],  
                        ],
                    ]); ?>

                    <h5 class="text-secondary border-bottom pb-2 mb-3">
                        <span class="badge bg-secondary me-2">1</span> Le Trajet
                    </h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ville de départ</label>
                            <?= Html::textInput('ville_depart', $villeDepart, [
                                'class' => 'form-control form-control-lg', 
                                'placeholder' => 'Ex: Toulouse'
                            ]) ?>
                            <div class="form-text">La ville d'où partira le véhicule.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ville d'arrivée</label>
                            <?= Html::textInput('ville_arrivee', $villeArrivee, [
                                'class' => 'form-control form-control-lg', 
                                'placeholder' => 'Ex: Marseille'
                            ]) ?>
                            <div class="form-text">La destination finale.</div>
                        </div>
                    </div>

                    <h5 class="text-secondary border-bottom pb-2 mb-3">
                        <span class="badge bg-secondary me-2">2</span> Le Véhicule
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <?= $form->field($model, 'idmarquev')->dropDownList(
                                $marquesVehicules, 
                                ['prompt' => 'Sélectionnez la marque...', 'class' => 'form-select form-select-lg']
                            )->label('Marque', ['class' => 'fw-bold']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'idtypev')->dropDownList(
                                $typesVehicules, 
                                ['prompt' => 'Catégorie du véhicule...', 'class' => 'form-select form-select-lg']
                            )->label('Type de véhicule', ['class' => 'fw-bold']) ?>
                        </div>
                    </div>

                    <h5 class="text-secondary border-bottom pb-2 mb-3">
                        <span class="badge bg-secondary me-2">3</span> Détails du voyage
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <?= $form->field($model, 'heuredepart')->input('number', [
                                'min' => 0, 'max' => 23, 'placeholder' => '14'
                            ])->label('Heure départ (H)', ['class' => 'fw-bold']) 
                            ->hint('Entre 0 et 23h') ?>
                        </div>
                        
                        <div class="col-md-3">
                            <?= $form->field($model, 'nbplacedispo')->input('number', [
                                'min' => 1, 'max' => 9, 'value' => 3
                            ])->label('Places dispo.', ['class' => 'fw-bold']) ?>
                        </div>

                        <div class="col-md-3">
                            <?= $form->field($model, 'tarif')->textInput([
                                'type' => 'number', 'step' => '0.01', 'placeholder' => '0.15'
                            ])->label('Tarif (€/km/pers)', ['class' => 'fw-bold']) ?>
                        </div>

                        <div class="col-md-3">
                            <?= $form->field($model, 'nbbagage')->input('number', [
                                'min' => 0, 'max' => 5, 'value' => 1
                            ])->label('Bagages / pers', ['class' => 'fw-bold']) ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <?= $form->field($model, 'contraintes')->textarea([
                            'rows' => 3, 
                            'placeholder' => 'Ex: Animaux acceptés en cage, pas de fumeur, bonne humeur obligatoire...'
                        ])->label('Contraintes particulières (facultatif)', ['class' => 'fw-bold']) ?>
                    </div>

                    <div class="d-grid gap-2">
                        <?= Html::submitButton('<i class="fas fa-check-circle me-2"></i> Publier le trajet', [
                            'class' => 'btn btn-success btn-lg shadow-sm py-3'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>