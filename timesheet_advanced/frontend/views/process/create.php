<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Process */

$this->title = 'Create Process';
$this->params['breadcrumbs'][] = ['label' => 'Processes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
