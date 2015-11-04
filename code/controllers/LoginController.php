<?php

namespace app\controllers;

use Yii;
use app\models\User;

class LoginController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new User();
//        $request = Yii::$app->request;
//        $post = $request->post('User');
        //$get = $request->get();

        if ($model->load(Yii::$app->request->post())){
            $request = Yii::$app->request->post('User');
            $username = $request['user_name'];
            $password = $request['password'];

            if ($model -> Login($username, $password)==true){
                //code
                Yii::$app->session->setFlash('loginSuccess');
            } else {
                //code
                Yii::$app->session->setFlash('loginFailed');
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionSignup()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->submit()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('signup', ['model' => $model]);
    }

}
