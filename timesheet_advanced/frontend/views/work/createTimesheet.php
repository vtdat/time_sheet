<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use frontend\models\TeamMember;
use frontend\models\Process;
?>

<?php $this->registerJs("
    $('.delete-button').click(function() {
        var detail = $(this).closest('.receipt-detail');    
        detail.remove();
    });
");
?>

<?php
    $userid=Yii::$app->user->getId();
    $teammember=TeamMember::findAll(['user_id'=>$userid]);
    $teamlist=[];
    foreach($teammember as $team){
        $teamlist[$team->team_id]=TeamMember::getTeamName($team->team_id);
    }
?>
<div class="receipt-form">
 
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'id' => 'form-login', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['autoPlaceholder'=>true,'showErrors'=>true]
    ]); ?>
    <?= "<h2>Timesheet Date:</h2>"?>
    
    <?php if(Yii::$app->session->hasFlash("NoModify")) { ?>
        <div class="alert alert-danger">Timesheet đã được Chấm điểm - Không thể add thêm</div>
    <?php } ?>
        
    <?= $form->field($model, 'date')->widget(
            DatePicker::className(),[
                'name' => 'dp_2',
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]
    ) ?>
    <?= "<h2>Details</h2>"?>
    
    <?php foreach ($modelDetails as $i => $modelDetail) : ?>
        <div class="row receipt-detail receipt-detail-<?= $i ?>">
            <div class="col-md-2"><?= 
                $form->field($modelDetail, "[$i]team_id" )->widget(
                    Select2::className(), [
                        'theme'=> 'bootstrap',
                        'data' => $teamlist,
                        'options' => ['placeholder' => 'Select team'],
                        'pluginOptions' => [
                                'allowClear' => true
                        ], 
                    ]
                )
//                $form->field($modelDetail, "[$i]team_id" )->dropDownList(
//                    $teamlist,
//                    ['prompt'=>'Select Team']
//                )
            ?></div>
            <div class="col-md-2">
                <?= $form->field($modelDetail, "[$i]process_id" )->dropDownList(
                    ArrayHelper::map(Process::find()->all(),'id','process_name'),
                    ['prompt'=>'Select Process']
                )?>
            </div>
            
            <div class="col-md-2"><?= $form->field($modelDetail, "[$i]work_name" )->textInput()?></div>
            <div class="col-md-2"><?= $form->field($modelDetail, "[$i]work_time" )->textInput()?></div>
            <div class="col-md-2"><?= $form->field($modelDetail, "[$i]comment" )->textInput()?></div>
            <div class="col-md-2">
                <?= Html::button('x', ['class' => 'delete-button btn btn-danger', 'data-target' => "receipt-detail-$i"]) ?>
            </div>
        </div>
      
    <?php endforeach; ?>
 
    <div class="form-group row"> 
        <div class="col-md-6"><?= Html::submitButton('Add row', ['name' => 'addRow', 'value' => 'true', 'class' => 'btn btn-info']) ?></div>
        <div class="col-md-6"><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>
    </div>
 
    <?php ActiveForm::end(); ?>
 
</div>