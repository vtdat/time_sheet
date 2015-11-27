<?php
use yii\helpers\Html;
use common\models\User;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
$this->title = 'Point';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    $gridColumns = [
            [
                'label' => 'Name',
                'attribute' => 'full_name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(User::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'Full name'],
                ],
            ],
            [
                'label' => 'Point',
                'value' => function($data){return User::calPoint($data->id, date('Y-m-d'));},
                'group' => true,
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'Point'],
                    ],
                'hAlign' => GridView::ALIGN_CENTER,
                'format' => ['decimal',2],
            ],
    ];
?>
<div class="container-fluid">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1> <br><br>
    
    <?php $form = ActiveForm::begin(); ?>

    
    <?php ActiveForm::end(); ?>

    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'hover' => true,
        ]);
    ?>
</div>