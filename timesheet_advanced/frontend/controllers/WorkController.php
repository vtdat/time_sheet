<?php

namespace frontend\controllers;
use yii\base\Model;
use Yii;

use frontend\models\Work;
use frontend\models\WorkSearch;
use frontend\models\Timesheet;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



/**
 * WorkController implements the CRUD actions for Work model.
 */
class WorkController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Work models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /*
        $dataProvider = new ActiveDataProvider([
            'query' => Work::find()->with('timesheet','process','team'),
        ]);
        */

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Work model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Work model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {   
        $model = new Timesheet(['user_id'=>$id]);
        $modelDetails = [];
 
        $formDetails = Yii::$app->request->post('Work', []);
        foreach ($formDetails as $formDetail) {
            $modelDetail = new Work();
            $modelDetail->setAttributes($formDetail);
            $modelDetails[] = $modelDetail;
        }
 
        //handling if the addRow button has been pressed
        if (Yii::$app->request->post('addRow') == 'true') {
            $model->load(Yii::$app->request->post());
            $modelDetails[] = new Work();
            return $this->render('createTimesheet', [
                'model' => $model,
                'modelDetails' => $modelDetails
            ]);
        }
 
        if ($model->load(Yii::$app->request->post())) {
            if (Model::validateMultiple($modelDetails) && $model->validate()) {
                $newmodel=Timesheet::findTimesheet($id,$model->date);
                if($newmodel==null){
                    Yii::$app->session->setFlash("CreateMode");
                    $model->save();   
                }
                else{
                    if($newmodel->status == 1){
                        Yii::$app->session->setFlash("NoModify");
                        return $this->render('createTimesheet',['model'=>$newmodel,'modelDetails'=>$modelDetails]);
                    }
                    //Yii::$app->session->setFlash("UpdateMode");
                    $model=$newmodel;
                }
                    
                foreach($modelDetails as $modelDetail) {
                        $modelDetail->timesheet_id = $model->id;
                        $modelDetail->save();
                }
                return $this->redirect(['index', 'id' => $model->id]);
            }
        }
 
        return $this->render('createTimesheet', [
            'model' => $model,
            'modelDetails' => $modelDetails
        ]);        
        
    }

    /**
     * Updates an existing Work model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Work model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Work model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Work the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Work::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
