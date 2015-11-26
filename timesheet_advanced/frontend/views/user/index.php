<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index container">
    <?php if(Yii::$app->session->hasFlash("NoDeleteRoot")) { ?>
        <div class="alert alert-danger">Cannot delete Director account!</div>
    <?php } ?>
        
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <div class="form-group" style="text-align: right;">
        <?= Html::a('Create <span class="glyphicon glyphicon-plus"></span>', 
            ['create'], 
            ['class' => 'btn btn-success']) 
        ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            'full_name',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'email:email',
             'status',
            // 'created_at',
            // 'updated_at',
            // 'address',
            // 'telephone',
            //'role',
            [
            'attribute' => 'role',
            'value' => function ($data) {
                return $data->role==0?'User':($data->role==1?'Admin':'Director');
            }
            ],
            // 'birthday',
            // 'avatar',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
