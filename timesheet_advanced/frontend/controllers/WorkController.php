<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Work;
use frontend\models\WorkSearch;
use frontend\models\Timesheet;
use frontend\models\Process;
use frontend\models\Team;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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

    /**
     * Creates a new Work model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
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
}
