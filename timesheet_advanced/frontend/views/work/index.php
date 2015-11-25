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

    <h1 style="text-align: center; margin-bottom: 40px;"><?= Html::encode($this->title) ?></h1>

    <?php if(Yii::$app->session->hasFlash('TimesheetDELETED')) { ?>
        <div class="alert alert-success">Timesheet has been deleted successfully!</div>
    <?php } ?>

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
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=>ArrayHelper::map(User::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
                'filterWidgetOptions' => [
                    'pluginOptions'=>['allowClear'=>true],
                    'options'=>['placeholder'=> 'Full name'],
                ],
                'group' => true,
                'subGroupOf' => 0,
            ],            
            // WORKTIME column
            [
                'attribute' => 'work_time', 
                'value' => function($data) {return $data->work_time.' hour(s)';},
                'width' => '2em',
                'mergeHeader' => true,
                'hAlign' => GridView::ALIGN_CENTER,
                'vAlign' => GridView::ALIGN_TOP,
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
                'vAlign' => GridView::ALIGN_TOP,      
            ],
            // COMMENT column
            [
                'label' => 'Comment',
                'attribute' => 'comment',
                'mergeHeader' => true,                
                'vAlign' => GridView::ALIGN_TOP,
            ],
            // POINT column
            [
                'label' => 'Point', 
                'attribute' => 'timesheet.point',
                'value' => function($data) {
                    return $data->timesheet->status == 0 ? '-' : $data->timesheet->point;
                },
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
                'value' => function($data) {
                    return $data->timesheet->status == 0 ? '(not commented yet)' : $data->timesheet->director_comment;
                },
                'group' => true,
                'groupOddCssClass' => false,
                'groupEvenCssClass' => false,
                'subGroupOf' => 1,
                'mergeHeader' => true,
                'vAlign' => GridView::ALIGN_TOP,
            ],
            // STATUS column
            /*
            [
                'attribute' => 'timesheet.status',
            ],
            */
            // ACTION column
            
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        $itsUser = $model->getUser()->one();
                        $itsTimesheet = $model->getTimesheet()->one();
                        if($itsUser['id'] == Yii::$app->user->identity->id
                            && $itsTimesheet['status'] == 0) {
                            $canEdit = 1;
                        } else {
                            $canEdit = 0;
                        }
                        if($canEdit) {
                            $url = '../web/index.php?r=timesheet/update&id='.$model['timesheet_id'];
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        }
                    },
                ],
                'viewOptions' => ['label' => false],

            ],
            
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        //'hover' => TRUE,
        'striped' => false,
        /*
        'rowOptions' => function ($model, $key, $index, $grid) {
            $itsUser = $model->getUser()->one();
            if($itsUser['id'] == Yii::$app->user->identity->id) {
                $isOwner = 1;
            } else {
                $isOwner = 0;
            }
            return [
                'id' => $model['timesheet_id'],
                'onclick' => 
                $isOwner ? 'window.location.href = "index.php?r=timesheet/update&id="+this.id;' :
                'window.location.href = "index.php?r=timesheet/view&id="+this.id;'
            ];
        },
        */
        /*
        'panel' => [
            'heading' => '<span class="glyphicon glyphicon-list-alt"></span>',
            'type' => GridView::TYPE_SUCCESS,
        ],
        */
        'toolbar' => [
            [
                'content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Create', 
                    ['work/create2','id' => Yii::$app->user->identity->id],['class'=>'btn btn-success']),
            ],
            '{toggleData}',
            '{export}',
        ],
        'export' => [
            'fontAwesome' => false,
            'label' => 'Export',
        ],
        
    ]); ?>
</div>