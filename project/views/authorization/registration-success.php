<?php

use yii\helpers\Html;

$this->title = 'Registration successfull';
?>

<div class="col-md-offset-3 col-lg-offset-3 col-md-6 col-lg-6">
    <div class="alert alert-success center">
        <strong><?= Html::encode($this->title) ?></strong>
        <p>Now you can <a href="/authorization/login">Login</a> to use your account</p>
    </div>
</div>