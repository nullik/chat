<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\RegistrationForm */
/* @var $form ActiveForm */
$this->title = 'Registration';
$this->registerJsFile('@web/js/registrationForm.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="col-md-offset-3 col-lg-offset-3 col-md-6 col-lg-6">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'confirmPassword')->passwordInput() ?>
        <?= $form->field($model, 'email') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
