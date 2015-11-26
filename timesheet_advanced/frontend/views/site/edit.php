<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use frontend\models\Team;
use frontend\models\TeamMember;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */
$this->title = 'Sửa Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    $user = $model;
    $model->team=TeamMember::getTeamListByUser($id);
?>

<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
<div class="container row">
<br/>
    <div class="">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= Html::Button($model->role==0?'User':($model->role==1?'Admin':'Director'), ['class' => $model->role==0?'btn btn-default':($model->role==1?'btn btn-success':'btn btn-danger')]) ?>
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
        <?php
            echo $form->field($model, 'imageFile')->fileInput() ;
        ?>
        <?= 
            $form->field($model, 'team')->widget(
            Select2::classname(), [
                'data' => ArrayHelper::map(Team::find()->all(),'id','team_name'),
                'options' => [
                    'placeholder' => 'Select a team ...',
                    'multiple' => true,
                ],
                'pluginOptions' => [ 
                ],
            ]);
        ?>
        <?= $form->field($model, 'password')->passwordInput([
            'placeholder'=>"Xác thực mật khẩu",
        ])?>
        
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
