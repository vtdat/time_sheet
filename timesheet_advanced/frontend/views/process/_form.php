<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Process */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="process-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'process_name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
