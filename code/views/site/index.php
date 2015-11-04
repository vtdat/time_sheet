<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Đăng Nhập';
?>
<div class="site-login">
    <h1 style="text-align: center; padding-top: 10%;"><?= Html::encode($this->title) ?></h1>

    <p style="text-align: center;">Điền tài khoản và mật khẩu !</p>

    <?php
        if (Yii::$app->session->hasFlash('loginSuccess')) {
    ?>
        <div style="text-align: center;" class="alert alert-success"> Đăng nhập thành công ! </div>
    <?php
        }
    ?>

    <?php
    if (Yii::$app->session->hasFlash('loginFailed')) {
        ?>
        <div style="text-align: center;" class="alert alert-danger"> Đăng nhập thất bại ! </div>
        <?php
    }
    ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div style =\"float:right\" class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'user_name') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    <div class="form-group">
        <div class="col-lg-offset-6 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
