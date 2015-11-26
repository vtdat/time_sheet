<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\editable\Editable;

use common\models\User;
use frontend\models\Process;
use frontend\models\Timesheet;
use frontend\models\Team;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Timesheet marking';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="work-index container-fluid">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
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
                'class'=>'kartik\grid\EditableColumn',
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
                'editableOptions' => [
                    'header' => 'Mark',
                    'inputType' => Editable::INPUT_TEXT,
                    'format'=> Editable::FORMAT_BUTTON,
                    'valueIfNull'=>'<em style="color:red">Not marked!</em>',
                    'preHeader'=>'<i class="glyphicon glyphicon-edit"></i>  ',
                    'type'=>'primary',
                    'placement'=>'left',
                    'options' => [
                        'placeholder'=>'Mark here',
                    ],
                ],
            ],
            // DRECTOR COMMENT column
            [
                'class'=>'kartik\grid\EditableColumn',
                'label' => 'Director Comment', 
                'attribute' => 'timesheet.director_comment',
                'group' => true,
                'groupOddCssClass' => false,
                'groupEvenCssClass' => false,
                'subGroupOf' => 1,
                'mergeHeader' => true,
                'editableOptions' => [
                    'submitOnEnter'=>false,
                    'asPopover'=>true,
                    'header' => 'Comment',
                    'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                    'valueIfNull'=>'<em style="color:red">Not commented!</em>',
                    'preHeader'=>'<i class="glyphicon glyphicon-edit"></i>  ',
                    'editableValueOptions'=>[
                        'style'=>'color: blue',
                    ],
                    'type'=>'primary',
                    'size'=>'lg',
                    'placement'=>'left',
                    'options' => [
                        'placeholder'=>'Comment here'
                    ]
                ],
                'vAlign' => GridView::ALIGN_TOP,
            ],
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        //'hover' => TRUE,
        'striped' => FALSE,
    ]); ?>
    
</div>
