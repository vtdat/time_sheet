<?php

namespace frontend\controllers;

use frontend\models\Work;
use frontend\models\WorkSearch;
use frontend\models\Timesheet;
use frontend\models\Process;
use frontend\models\Team;
use frontend\models\CreateTimesheetForm;

use yii\base\Model;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

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
        $this->allowUser(2);
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->chamdiem(Yii::$app->request->queryParams);
        if (Yii::$app->request->post('hasEditable')){
            $workId= Yii::$app->request->post('editableKey');
            $timesheetId=Work::findOne($workId)->timesheet_id;
            $model = Timesheet::findOne($timesheetId);
            $post = current($_POST['Work']);
            $error=Json::encode(['output'=>'', 'message'=>'Validate error']);
            foreach($post as $postname => $value){
                if($postname=="timesheet.point"){
                    $model->point=$value;
                    if ($value !== null) {
                        if(($value<0)||($value>2)){ 
                            return Json::encode(['output'=>'', 'message'=>'chỉ được nhập từ 0 đến 2']);
                        }   
                        $model->status = 1;
                    }
                    else{
                        $model->status = 0;
                    }
                }
                if($postname=="timesheet.director_comment"){
                    $model->director_comment=$value;
                }
            }
            
            if($model->validate()){
                $model->save();
            }
            else{
                return $error;
            }
            return Json::encode(['output'=>'', 'message'=>'']);
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

    public function actionCreate()
    {   
        $id=Yii::$app->user->identity->id;
        $model = new Timesheet(['user_id'=>$id,'date'=>date('Y-m-d')]);
        $modelDetails = [new Work(),new Work(),new Work()];
        
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
            //if (Model::validateMultiple($modelDetails) && $model->validate()) {
            if ($model->validate()) {
                $newmodel=Timesheet::findTimesheet($id,$model->date);
                if($newmodel==null){
                    if (\strtotime($model->date) > \strtotime(\date('Y-m-d')) ){
                        Yii::$app->session->setFlash("WrongDate");
                        $model->date=date('Y-m-d');
                        return $this->render('createTimesheet',['model'=>$model,'modelDetails'=>$modelDetails]);
                    }
                    else{
                        Yii::$app->session->setFlash("CreateMode");
                        $model->save();  
                    }    
                }
                else{
                    if($newmodel->point !== null){
                        Yii::$app->session->setFlash("NoModify");
                        return $this->render('createTimesheet',['model'=>$newmodel,'modelDetails'=>$modelDetails]);
                    }
                    $model=$newmodel;
                }
                foreach($modelDetails as $modelDetail) {
                    $modelDetail->timesheet_id = $model->id;
                    if ($modelDetail->validate()){
                        $modelDetail->save();
                    }
                }
                return $this->redirect(['index', 'id' => $model->id]);
            }
        }
 
        return $this->render('createTimesheet', [
            'model' => $model,
            'modelDetails' => $modelDetails
        ]);        
        
    }

    public function actionCreate2()
    {
        $formatter = Yii::$app->formatter;
        $work = new Work();
        $timesheet = new Timesheet();
        $isUpdated = false;
        $isCreated = false;
        if(Yii::$app->request->post()){
            $formAttributes = Yii::$app->request->post('createForm');
            $date = $formAttributes['date'];
                        
            $availTimesheet = Timesheet::find()
                ->where(['date' => $date, 'user_id' => Yii::$app->user->identity->id])->one();
            if($availTimesheet) { // timesheet is available
                if($availTimesheet->status) {
                    Yii::$app->session->setFlash('CreateTimesheetFailed');
                    return $this->render('create');
                }
                $work->timesheet_id = $availTimesheet->id;
                $availTimesheet->updated_at = time();
                if($availTimesheet->update()) {
                    $isUpdated = true;
                }
            } else {
                $timesheet->created_at = time();
                $timesheet->updated_at = time();
                $timesheet->date = $date;
                $timesheet->user_id = Yii::$app->user->identity->id;
                $timesheet->point = 0;
                $timesheet->director_comment = '';
                $timesheet->status = 0;
                if($timesheet->save()) {
                    $isCreated = true;
                }
            }
            if($isUpdated || ($isCreated && $work->timesheet_id = $timesheet->id)) 
            {                
                $process = Process::find()->where(['process_name' => $formAttributes['process_name']])->one();
                $team = Team::find()->where(['team_name' => $formAttributes['team_name']])->one();
                                
                $work->process_id = $process->id;
                $work->team_id = $team->id;
                $work->work_time = $formAttributes['work_time'];
                $work->work_name = $formAttributes['work_name'];
                $work->comment = $formAttributes['comment'];
                $work->created_at = time();
                $work->updated_at = time();
                if($work->save()) {
                    return $this->goBack();
                } else {
                    return $this->render('create');
                }
            }
        }
        
        return $this->render('create');
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
