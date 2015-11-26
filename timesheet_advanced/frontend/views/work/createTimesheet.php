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
use common\models\User;
?>
<!--
<head>
    <style type="text/css">
    .work-form * {border: 1px solid gray;}
    </style>
</head>
-->

<?php $this->registerJs("
    $('.delete-button').click(function() {
        var detail = $(this).closest('.work-detail');    
        detail.remove();
    });
");
?>

<?php
    $this->title = 'Create timesheet';
    $userid=Yii::$app->user->getId();
    $teammember=TeamMember::findAll(['user_id'=>$userid]);
    $teamlist=[];
    foreach($teammember as $team){
        $teamlist[$team->team_id]=TeamMember::getTeamName($team->team_id);
    }
?>

<div class="work-form container">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-warning">
        <span class="glyphicon glyphicon-info-sign"></span>
        <?="  Your average point of this month is <strong>".User::calPoint($userid,date('Y-m-d'))."</strong>"?>
    </div>
 
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'id' => 'form-login', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => [
            'autoPlaceholder'=>true,
            'showErrors'=>true,
        ],
    ]); ?>
    
    
    <?php if(Yii::$app->session->hasFlash("NoModify")) { ?>
        <div class="alert alert-danger">
            <strong>Cannot modify! </strong>
            This timesheet has been marked!
        </div>
    <?php } ?>
        
    <?php if(Yii::$app->session->hasFlash("WrongDate")) { ?>
        <div class="alert alert-danger">
            <strong>Cannot create! </strong>
            You cannot create timesheet of tommorrow!
        </div>
    <?php } ?> 
        
    <h2>Choose date:</h2>

    <?= $form->field($model, 'date')->widget(
            DatePicker::className(),[
                'name' => 'dp_2',
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]
    ) ?>
    
    <h2>Details:</h2>
    
    <?php foreach ($modelDetails as $i => $modelDetail) : ?>
        <div class="row work-detail work-detail-<?= $i ?>">
            <div class = "col-md-2"><?= 
                $form->field($modelDetail, "[$i]team_id" )->widget(
                    Select2::className(), [
                        'theme'=> 'bootstrap',
                        'data' => $teamlist,
                        'options' => ['placeholder' => 'Select team'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '10em',
                        ], 
                    ]
                )
            ?></div>
            <div class="col-md-2">
                <?= $form->field($modelDetail, "[$i]process_id" )->widget(
                    Select2::className(), [
                        'data' => ArrayHelper::map(Process::find()->all(),'id','process_name'),
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '10em',
                        ],
                        'options' => ['placeholder' => 'Select Process'],
                    ]
                )?>
            </div>
            <div class="col-md-2"><?= $form->field($modelDetail, "[$i]work_time" )->textInput()?></div>
            <div class="col-md-2"><?= $form->field($modelDetail, "[$i]work_name" )->textInput()?></div>
            <div class="col-md-2"><?= $form->field($modelDetail, "[$i]comment" )->textarea(['rows'=>1])?></div>
            <div class="col-md-2" style="padding-left: 30px;padding-bottom: 10px">
                <?= Html::button('<span class="glyphicon glyphicon-remove"></span>', ['class' => 'delete-button btn btn-danger', 'data-target' => "work-detail-$i"]) ?>
            </div>
        </div>
      
    <?php endforeach; ?>
 

    <div class="form-group" style="text-align: right; margin-top: 20px;"> 
        <?= Html::submitButton('Add row <span class="glyphicon glyphicon-plus"></span>', ['name' => 'addRow', 'value' => 'true', 'class' => 'btn btn-primary']) ?>
        <?= Html::submitButton(
            $model->isNewRecord ? 'Create <span class="glyphicon glyphicon-ok"></span>' : 'Update <span class="glyphicon glyphicon-ok"></span>', 
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
 
    <?php ActiveForm::end(); ?>
 
</div>