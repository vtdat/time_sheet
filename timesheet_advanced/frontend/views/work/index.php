<?php

use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\ExpandRowColumn;

use common\models\User;
use frontend\models\Process;
use frontend\models\Timesheet;
use frontend\models\Team;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'HBLab Timesheets';
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;
?>
<div class="work-index container-fluid">

    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <div class="form-group" style="text-align: right; margin-top: 40px;">
        <?= Html::a('Create',['work/create'],['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Export  <span class="glyphicon glyphicon-save"></span>', ['class' => 'btn btn-success']) ?>
    </div>

    <?php 
        $gridColumns = [
            // DATE column
            [
                'attribute' => 'timesheet.date', 
                'format' => 'date',
                'group' => true,
                'groupOddCssClass' => false,
                'groupEvenCssClass' => false,
                'width' => '13em',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => 
                        [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                    'removeButton' => false,
                ],
                'hAlign' => GridView::ALIGN_CENTER,
            ],
            // FULL NAME column
            [
                'label' => 'User', 
                'attribute' => 'user.full_name',
                'width' => '15em',
                'group' => true,
                'subGroupOf' => 0,
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=>ArrayHelper::map(User::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
                'filterWidgetOptions' => [
                    'pluginOptions'=>['allowClear'=>true],
                    'options'=>['placeholder'=> 'Full name'],
                ],
            ],            
            // WORKTIME column
            [
                'attribute' => 'work_time', 
                'value' => function($data) {return $data->work_time.' hour(s)';},
                'width' => '2em',
                'mergeHeader' => true,
                'hAlign' => GridView::ALIGN_CENTER,
            ],
            // TEAM column
            [
                'label' => 'Team',
                'attribute' => 'team.team_name', 
                'width' => '5em',
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
                'width' => '9em',
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
                'mergeHeader' => true,                
            ],
            // POINT column
            [
                'label' => 'Point', 
                'attribute' => 'timesheet.point',
                'group' => true,
                'groupOddCssClass' => false,
                'groupEvenCssClass' => false,
                'subGroupOf' => 1,
                'width' => '2.2em',                
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=>ArrayHelper::map(Timesheet::find()->orderBy('point')->asArray()->all(), 'point', 'point'),
                'filterWidgetOptions' => [
                    'pluginOptions'=>['allowClear'=>true],
                    'options'=>['placeholder'=> 'Point'],
                ],
                'hAlign' => GridView::ALIGN_CENTER,
            ],
            // DRECTOR COMMENT column
            [
                'label' => 'Director Comment', 
                'attribute' => 'timesheet.director_comment',
                'group' => true,
                'groupOddCssClass' => false,
                'groupEvenCssClass' => false,
                'subGroupOf' => 1,
                'mergeHeader' => true,
            ],
            // STATUS column
            /*
            [
                'attribute' => 'timesheet.status',
            ],
            */
            // ACTION column
            /*
            [
                'class' => 'kartik\grid\ActionColumn',

            ],
            */
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'hover' => TRUE,
        'striped' => FALSE,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'id' => $model['timesheet_id'],
                'onclick' => 'window.location.href = "index.php?r=timesheet/view&id="+this.id;'
            ];
        },
    ]); ?>


</div>
