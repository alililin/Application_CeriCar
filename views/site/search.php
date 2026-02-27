<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'CERICar - Voyagez simplement';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

// Récupération de l'état de la checkbox
$correspondanceChecked = Yii::$app->request->get('correspondance', false);
?>

<div class="hero-wrapper" style="background-image: url('<?= Url::to('@web/images/car.jpg') ?>');">
    <div class="hero-overlay"></div>
</div>

<div class="search-card search-floating">
    
    <?= Html::beginForm(['site/search'], 'get', ['id' => 'search-form', 'class' => 'd-flex w-100 align-items-center flex-wrap']) ?>
        
        <div class="search-group">
            <label class="search-label">Départ</label>
            <?= Html::textInput('ville_depart', $villeDepart ?? '', ['class' => 'custom-input', 'placeholder' => 'Ex: Avignon', 'id' => 'ville_depart']) ?>
        </div>
        
        <div class="search-group">
            <label class="search-label">Arrivée</label>
            <?= Html::textInput('ville_arrivee', $villeArrivee ?? '', ['class' => 'custom-input', 'id' => 'ville_arrivee', 'placeholder' => 'Ex: Paris']) ?>
        </div>
        
        <div class="search-group" style="max-width: 120px;">
            <label class="search-label">Voyageurs</label>
            <?= Html::input('number', 'nb_personnes',  $nbPersonnes ?? 1, ['class' => 'custom-input', 'id' => 'nb_personnes', 'min' => 1]) ?>
        </div>

        <div class="search-group d-flex align-items-center px-3" style="border-right: none;">
            <div class="form-check">
                <?= Html::checkbox('correspondance', $correspondanceChecked, [
                    'class' => 'form-check-input', 
                    'id' => 'checkCorrespondance',
                    //Si la case est cochée, la valeur envoyée au serveur sera 1
                    'value' => 1
                ]) ?>
                <label class="form-check-label text-muted" for="checkCorrespondance" style="margin-left: 8px; cursor: pointer;">
                    Correspondances ?
                </label>
            </div>
        </div>

        <button type="submit" class="btn-search-main btn-search-text ml-auto">
            Rechercher
        </button>

    <?= Html::endForm() ?>
</div>
     
<div class="container mt-5" >
    <div id="results-wrapper">
    </div>
</div>