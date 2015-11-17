<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\i18n\Formatter;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

use frontend\models\Process;
use frontend\models\Team;

/* @var $this yii\web\View */
/* @var $model app\models\Timesheet */

$formatter = Yii::$app->formatter;

$this->title = $formatter->asDate($model->date,'medium');
?>

<!--
<div class="timesheet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'point',
            'director_comment',
            'date',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
-->

<div class="timesheet-view container">
    
<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<p><span style="font-weight: bold;">Point: </span></p>
<p><span style="font-weight: bold;">Director comment: </span></p>

<br />

<?php 

$gridColumns = [
    [
        'class' => '\kartik\grid\SerialColumn',
    ],
    // WORK TIME column
    [
        'label' => 'Work Time',
        'attribute' => 'work_time',
        'value' => function($data) {return $data->work_time.' hour(s)';},
        'mergeHeader' => true,
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    // TEAM column
    [
        'label' => 'Team',
        'attribute' => 'team.team_name',
        'filterType' => GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Team::find()->orderBy('team_name')->asArray()->all(), 'team_name', 'team_name'),
        'filterWidgetOptions' => [
            'pluginOptions'=>['allowClear'=>true],
                'options'=>['placeholder'=> 'Team'],
        ],

    ],
    // PROCESS column
    [
        'label' => 'Process',
        'attribute' => 'process.process_name',
        'filterType' => GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Process::find()->orderBy('process_name')->asArray()->all(), 'process_name', 'process_name'),
        'filterWidgetOptions' => [
            'pluginOptions'=>['allowClear'=>true],
            'options'=>['placeholder'=> 'Process'],
        ],
    ],
    // WORK_NAME column
    [
        'label' => 'Work Details',
        'attribute' => 'work_name',
        'mergeHeader' => true,                
    ],
    // COMMENT column
    [
        'label' => 'Comment',
        'attribute' => 'comment',
    ],
];  

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns,
    'hover' => true,
    'striped' => false,

]); ?>

</div>