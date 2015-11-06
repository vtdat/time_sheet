<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Timesheet */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Timesheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timesheet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'user_id' => $model->user_id], [
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
            'comment:ntext',
            'date',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
