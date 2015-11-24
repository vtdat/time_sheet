<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Profile';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<head>
    <style type="text/css">
    .tittle {display: inline-block; right: 30px;}
    </style>
</head>

<body>

<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<div class="col-md-3 col-md-offset-2">
    <?php echo Html::img('../uploads/' . $model->avatar, ['alt'=>'', 'style' => 'width:95%;',]);
    ?>
</div>

<div class="col-md-6">
    <h3 style="font-weight: bold;"><?= Html::encode($model->full_name) ?></h3>
    <h4 style="font-style: italic;"><?= Html::encode($model->username) ?></h4>
    <?php
    if ($model->role == 0) {
    ?>
    <button class="btn btn-primary btn-sm">User</button>
    <?php } ?>
    <?php
    if ($model->role == 1) {
        ?>
        <button class="btn btn-primary btn-sm">Admin</button>
    <?php } ?>
    <?php
    if ($model->role == 2) {
        ?>
        <button class="btn btn-primary btn-sm">Director</button>
    <?php } ?>
    <br /><br />
    
    <h4 style="font-weight: bold;">General information <a href="index.php?r=site/edit" class="btn btn-info" style="float:right; font-size: 12px;" role="button" >Edit</a></h4>
    <div class="block" style="border-bottom: 2px solid #eee;"></div>

    <div id="contentBox" style="margin:20px auto 0 auto; width:100%">
        <div id="column1" style="float:left; margin:0; width: 20%;">
            <p>Name:</p>
            <p>Username:</p>
            <p>Address:</p>
        </div>

        <div id="column2" style="float:left; margin:0;">
            <p><?= Html::encode(isset($model->full_name) ? $model->full_name : '') ?></p>
            <p><?= Html::encode(isset($model->username) ? $model->username : '') ?></p>
            <p><?= Html::encode(isset($model->address) ? $model->address : '') ?></p>
        </div>
    </div>
    <br clear="left">
    <h4 style="font-weight: bold;">Contact information</h4>
    <div class="block" style="border-bottom: 2px solid #eee;"></div>

    <div id="contentBox" style="margin:20px auto 0 auto; width:100%">
        <div id="column1" style="float:left; margin:0; width: 20%;">
            <p>Email:</p>
            <p>Telephone:</p>
        </div>

        <div id="column2" style="float:left; margin:0;">
            <p><?= Html::encode(isset($model->email) ? $model->email : '') ?></p>
            <p><?= Html::encode(isset($model->telephone) ? $model->telephone : '') ?></p>
        </div>
    </div>
</div>

</body>
