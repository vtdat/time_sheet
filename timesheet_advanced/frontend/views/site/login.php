<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login col-md-6 col-md-offset-3" style="margin-top: 15%;">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Enter username']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Enter password']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div style="color:#999;margin:1em 0">
        If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
    </div>

    <div class="form-group text-center" style="margin-top: 40px;">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>