<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Timesheet;
use frontend\models\TimesheetSearch;
use frontend\models\Work;
use frontend\models\WorkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * TimesheetController implements the CRUD actions for Timesheet model.
 */
class TimesheetController extends Controller
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
     * Lists all Timesheet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimesheetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Timesheet model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = Work::find()->where(['timesheet_id' => $id])->joinWith(['process','team']);

        $searchModel = new WorkSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'process.process_name' => [
                        'asc' => ['process.process_name' => SORT_ASC],
                        'desc' => ['process.process_name' => SORT_DESC],
                    ],
                    'team.team_name' => [
                        'asc' => ['team.team_name' => SORT_ASC],
                        'desc' => ['team.team_name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Timesheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Timesheet();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->user_id = Yii::$app->user->identity->id;
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);                
            } else {
                    return $this->render('create', [
                    'model' => $model,
                ]);    
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Timesheet model.
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
     * Deletes an existing Timesheet model.
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
     * Finds the Timesheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Timesheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Timesheet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
