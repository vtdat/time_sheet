<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TeamMember */

$this->title = 'Create Team Member';
$this->params['breadcrumbs'][] = ['label' => 'Team Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
