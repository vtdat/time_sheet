<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php if(Yii::$app->session->hasFlash("NoDeleteRoot")) { ?>
        <div class="alert alert-danger">Không thể xóa Director. You only a admin. Kid !</div>
    <?php } ?>
        
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'email:email',
             'status',
            // 'created_at',
            // 'updated_at',
            // 'address',
             'full_name',
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
