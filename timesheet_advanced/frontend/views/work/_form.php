<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Work */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'timesheet_id')->textInput() ?>

    <?= $form->field($model, 'team_id')->textInput() ?>

    <?= $form->field($model, 'process_id')->textInput() ?>

    <?= $form->field($model, 'work_time')->textInput() ?>

    <?= $form->field($model, 'work_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
