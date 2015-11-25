<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'HBLab',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest == TRUE) {
        $menuItemsRight[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItemsRight[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItemsRight[] = [
            'label' => Yii::$app->user->identity->username,
            'url' => Url::toRoute(['/site/profile'])
        ];
        $menuItemsRight[] = [
            'label' => 'Logout',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
        $menuItemsLeft[] = ['label' => 'View', 'url' => ['/work/']];   
        $menuItemsLeft[] = ['label' => 'Create', 'url' => ['/work/create']];
        $menuItemsLeft[] = ['label' => 'Statistics', 'url' => ['/site/point']];
        if(Yii::$app->user->identity->role>=2){
            $menuItemsLeft[] = ['label' => 'Mark', 'url' => ['/work/chamdiem']];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => $menuItemsLeft,
        ]);
       
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItemsRight,
    ]);
    
    if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role>=1){
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Manage', 'items' => [
                        ['label' => 'User manage', 'url' => ['/user/']],
                        ['label' => 'Team manage', 'url' => ['/team/']],
                        ['label' => 'Process manage', 'url' => ['/process/']],
                    ]],
                ],
            ]);
    }
    
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<!--
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
-->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
