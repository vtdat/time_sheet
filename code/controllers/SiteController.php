<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SiteController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();

        if ($model->load(Yii::$app->request->post())){
            $request = Yii::$app->request->post('User');
            $username = $request['user_name'];
            $password = $request['password'];

            if ($model -> userLogin($username, $password)==true){
                Yii::$app->session->setFlash('loginSuccess');
                $model1 = new User();
                return $this->render('signup', ['model' => $model1]);
            } else {
                //code
                Yii::$app->session->setFlash('loginFailed');
            }
        }
        return $this->render('index', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->submit()) {
            Yii::$app->session->setFlash('createUserSuccuess');

            return $this->refresh();
        }
        return $this->render('signup', ['model' => $model]);
    }

}
