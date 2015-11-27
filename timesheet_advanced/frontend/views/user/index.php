<?php

use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\grid\DataColumn;

use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index container-fluid">
    <?php if(Yii::$app->session->hasFlash("NoDeleteRoot")) { ?>
        <div class="alert alert-danger">Cannot delete Director account!</div>
    <?php } ?>
        
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <?php 
        $gridColumns = [
            // USERNAME column
            [
                'attribute' => 'username',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(User::find()->orderBy('username')->asArray()->all(), 'username', 'username'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'All'],
                ],
                'headerOptions' => ['style' => 'text-align: center;'],
                'width' => '10em',
            ],  
            // FULLNAME column
            [
                'attribute' => 'full_name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(User::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'All'],
                ],
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            // ROLE column
            [
                'attribute' => 'role',
                'value' => function($data) {
                    return $data->getRole($data->id);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(User::$roles,'key', 'role'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'All'],    
                ],
                'width' => '9em',
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            // STATUS column
            [
                'attribute' => 'status',
                'value' => function($data) {
                    return $data->getStatus($data->id);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(User::$status,'key', 'status'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'All'],    
                ],
                'width' => '10em',
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            // BIRTHDAY column
            [
                'attribute' => 'birthday',
                'value' => function($data) {
                    $formatter = Yii::$app->formatter;
                    if(isset($data->birthday)) {
                        return $formatter->asDate($data->birthday,'medium');    
                    } else {
                        return '';
                    }                    
                },
                'mergeHeader' => true,
                'hAlign' => GridView::ALIGN_CENTER,
                'vAlign' => GridView::ALIGN_TOP,
            ],
            // TELEPHONE column
            [
                'attribute' => 'telephone',
                'value' => function($data) {
                    if(isset($data->telephone)) {
                        return $data->telephone;
                    } else {
                        return '';
                    }
                },
                'mergeHeader' => true,
                'hAlign' => GridView::ALIGN_CENTER,
                'vAlign' => GridView::ALIGN_TOP,
            ],
            // EMAIL column
            [
                'attribute' => 'email',
                'mergeHeader' => true,
                'vAlign' => GridView::ALIGN_TOP,
                'headerOptions' => ['style' => 'text-align: center;'],
            ],
            // ACTION columnm
            [
                'class' => 'kartik\grid\ActionColumn',
            ],
        ];
    ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::a('Create <span class="glyphicon glyphicon-plus"></span>', 
            ['create'], 
            ['class' => 'btn btn-success']) 
        ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'hover' => true,
        'striped' => false,
    ]); ?>

</div>
