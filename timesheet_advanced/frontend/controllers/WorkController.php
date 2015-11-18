<?php

namespace frontend\controllers;
use yii\base\Model;
use Yii;

use frontend\models\Work;
use frontend\models\WorkSearch;
use frontend\models\Timesheet;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\i18n\Formatter;
use yii\data\ActiveDataProvider;


/**
 * WorkController implements the CRUD actions for Work model.
 */
class WorkController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
//                'only' => ['create', 'update'],
                'only' => [],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
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
        $dataProvider->pagination = [
            'pageSize' => 10,
        ];
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

    public function actionChamdiem(){
        $this->allowUser(1);
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->chamdiem(Yii::$app->request->queryParams);
        if (Yii::$app->request->post('hasEditable')){
            $workId= Yii::$app->request->post('editableKey');
            $timesheetId=Work::findOne($workId)->timesheet_id;
            $model = Timesheet::findOne($timesheetId);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            
            $post = current($_POST['Work']);
            foreach($post as $postname => $value){
                if($postname=="timesheet.point"){
                    $model->point=$value;
                    $model->status=1;
                }
                if($postname=="timesheet.director_comment"){
                    $model->director_comment=$value;
                }
            }
            
            if($model->validate()){
                $model->save();
            }
            
            echo $out;
            return ;
        }

        return $this->render('chamdiem', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $modelDetails = [new Work()];
        $formDetails = Yii::$app->request->post('Work', []);
        foreach ($formDetails as $i=>$formDetail) {
            if(isset($modelDetails[$i])){
                $modelDetail = $modelDetails[$i];
                $modelDetail->setAttributes($formDetail);
            }
            else{
                $modelDetail = new Work();
                $modelDetail->setAttributes($formDetail);
                $modelDetails[] = $modelDetail;
            }
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
                    if($newmodel->point !== null){
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
    
    public function allowUser($level){
        $cur_level=Yii::$app->user->identity->role;
        if ($cur_level < $level) {
            throw new HttpException(403, 'You have no permission to view this content');
        }
    }
}
