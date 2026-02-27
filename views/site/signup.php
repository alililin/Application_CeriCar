<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Inscription';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Inscription</h2>
        <p class="auth-subtitle">Je souhaite m'inscrire en tant que :</p>

        <?php 
        $form = ActiveForm::begin([
            'id' => 'form-signup',
            'fieldConfig' => [
             
                'template' => "<div class=\"form-group-modern\">{label}\n{input}</div>",
                'inputOptions' => ['class' => 'input-modern'],
                'labelOptions' => ['class' => 'auth-label'], 
                'options' => ['autocomplete' => 'off'],
            ], 
        ]); 
        ?>

        <div class="mb-4 text-center">
            <?= $form->field($model, 'type_compte')->radioList(
                ['voyageur' => 'Voyageur (Passager)', 'conducteur' => 'Conducteur'],
                [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $check = $checked ? 'checked' : '';
                        return "<label class='me-3'><input type='radio' name='$name' value='$value' $check> $label</label>";
                    },
                    'class' => 'auth-label'
                ]
            )->label(false) ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'prenom')->textInput(['placeholder' => 'Prénom']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'nom')->textInput(['placeholder' => 'Nom']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'pseudo')->textInput(['placeholder' => 'Votre pseudo']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••']) ?>
            </div>
        </div>

        <?= $form->field($model, 'email')->textInput(['placeholder' => 'exemple@email.com']) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'permis')->textInput(['type' => 'number', 'placeholder' => 'N° Permis (Conducteurs uniquement)']) ?>
            </div>
            
            <div class="col-md-6">
                <?= $form->field($model, 'photo')->textInput(['placeholder' => 'URL Photo (facultatif)']) ?>
            </div>
        </div>

        <?= Html::submitButton("S'inscrire", ['class' => 'btn-auth w-100', 'name' => 'signup-button']) ?>

        <?php ActiveForm::end(); ?>

        <div class="auth-footer mt-3">
            Déjà inscrit ? <a class="ajax-link" href="<?= Url::to(['site/login']) ?>">Connexion</a>
        </div>
    </div>
</div>