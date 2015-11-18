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

<div class="timesheet-view container">
    
<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>


<?php if($model->status == 0) { ?>
<div class="alert alert-danger">
    <span class="glyphicon glyphicon-alert">  </span>
    This timesheet has not been marked yet!
</div>
<?php } else { ?>
<p>
    <span style="font-weight: bold;">Point: </span>
    <?= Html::encode($model->point) ?>
</p>
<p>
    <span style="font-weight: bold;">Director comment: </span>
    <?= Html::encode($model->director_comment) ?>
</p>
<?php } 

if(Yii::$app->session->hasFlash('updateOK')) { ?>
<div class="alert alert-success">
    Updated successfully!
</div>
<?php } ?>


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
        'mergeHeader' => true,
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
        'mergeHeader' => true,
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
        'mergeHeader' => true,
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
        'mergeHeader' => true,
    ],
];  

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns,
    'hover' => true,
    'striped' => false,
    'headerRowOptions' => ['style' => 'color: black; background: #eee; border-color: #337ab7;']
]); ?>

<?php if($model->user_id === Yii::$app->user->identity->id) { ?>
    
<div class="form-group" style="text-align: right;">
    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Edit',
        'index.php?r=timesheet/update&id='.$model->id,
        ['class' => $model->status ? 'btn btn-primary disabled' : 'btn btn-primary']) ?>
    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Delete all',
        ['\timesheet\delete'],
        ['class' => $model->status ? 'btn btn-danger disabled' : 'btn btn-danger']) ?>
</div>

<?php } ?>
</div>

