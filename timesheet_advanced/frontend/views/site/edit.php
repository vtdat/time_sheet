<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use frontend\models\Team;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */
$this->title = 'Sửa Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    //$user = Yii::$app->user->identity;
    $user = $model;
    $data=Team::find()->all();
    
?>

<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>


<div class="form-group row">
        <div ><?= Html::submitButton($model->role<1 ? 'User' : 'Admin', ['class' => $model->role<1 ? 'btn btn-default' : 'btn btn-primary']) ?></div>
</div>

    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        
        <?= $form->field($model, 'username')->textInput([
            'value'=>$user->username,
            'disabled'=>true,
        ]) ?>
        <?= $form->field($model, 'email')->textInput([
            'value'=>$user->email,
        ])?>
        <?= $form->field($model, 'full_name')->textInput([
            'placeholder'=>"Nhập họ và tên",
            'value'=>$user->full_name,
        ])?>
        <?= $form->field($model, 'birthday')->widget(
            DatePicker::className(),[
                'name' => 'dp_2',
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]
        ) ?>
        <?= $form->field($model, 'address')->textInput([
            'placeholder'=>"Nhập địa chỉ",
            'value'=>$user->address,
        ])?>
        <?= $form->field($model, 'telephone')->textInput([
            'placeholder'=>"Nhập số điện thoại",
            'value'=>$user->telephone,
        ])?>
        <?= $form->field($model, 'imageFile')->fileInput([
            'placeholder'=>"Upload ảnh",
        ]) ?>

        <?= $form->field($model, 'avatar')->textInput([
            'placeholder'=>"Nhập a",
            'value'=>$user->avatar,
        ])?>    
   
        <?= 
            $form->field($model, 'team')->widget(
            Select2::classname(), [
                'data' => ArrayHelper::map(Team::find()->all(),'id','team_name'),
                'options' => ['placeholder' => 'Select a team ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => false,
            ],
        ]);
        ?>
        
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
