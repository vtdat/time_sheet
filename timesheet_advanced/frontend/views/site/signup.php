<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
?>
<div class="site-signup col-md-8 col-md-offset-2">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Enter username']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Enter password']) ?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Enter email']) ?>
    
    <?= $form->field($model, 'full_name')->textInput(['placeholder' => 'Enter full name']) ?>

    <?= $form->field($model, 'address')->textInput(['placeholder' => 'Enter address']) ?>

    <?= $form->field($model, 'telephone')->textInput(['placeholder' => 'Enter phone number']) ?>

    <div class="form-group text-center" style="margin-top: 40px;">
        <?= Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Signup', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
