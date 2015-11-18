<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorkSearch represents the model behind the search form about `frontend\models\Work`.
 */
class WorkSearch extends Work
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'timesheet_id', 'team_id', 'process_id', 'work_time', 'created_at', 'updated_at'], 'integer'],
            [['work_name', 'comment'], 'safe'],
            [
                [ 
                    'process.process_name',
                    'team.team_name',
                    'timesheet.date',
                    'timesheet.point',
                    'timesheet.director_comment',
                    'timesheet.status',
                    'timesheet.created_at',
                    'user.full_name',
                ],
                'safe'
            ],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),
            [
                'process.process_name',
                'team.team_name',
                'timesheet.date', 
                'timesheet.point',
                'timesheet.director_comment',
                'timesheet.status',
                'timesheet.created_at',
                'user.full_name',
            ]);
        //print_r(parent::attributes()); exit;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Work::find()->indexBy('id');

        $query->joinWith(['timesheet','process','team','user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                        'timesheet.date' => SORT_DESC,
                        'timesheet.updated_at' => SORT_DESC,
                ],
                'attributes' => [
                    'work_time',
                    'work_name',
                    'comment',
                    'timesheet.date' => [
                        'asc' => ['timesheet.date' => SORT_ASC],
                        'desc' => ['timesheet.date' => SORT_DESC],
                    ],
                    'timesheet.updated_at' => [
                        'asc' => ['timesheet.updated_at' => SORT_ASC],
                        'desc' => ['timesheet.updated_at' => SORT_DESC],
                    ],
                    'process.process_name' => [
                        'asc' => ['process.process_name' => SORT_ASC],
                        'desc' => ['process.process_name' => SORT_DESC],
                    ],
                    'team.team_name' => [
                        'asc' => ['team.team_name' => SORT_ASC],
                        'desc' => ['team.team_name' => SORT_DESC],
                    ],
                    'timesheet.point' => [
                        'asc' => ['timesheet.point' => SORT_ASC],
                        'desc' => ['timesheet.point' => SORT_DESC],
                    ],
                    'timesheet.director_comment' => [
                        'asc' => ['timesheet.director_comment' => SORT_ASC],
                        'desc' => ['timesheet.director_comment' => SORT_DESC],
                    ],
                    'timesheet.status' => [
                        'asc' => ['timesheet.status' => SORT_ASC],
                        'desc' => ['timesheet.status' => SORT_DESC],
                    ],
                    'user.full_name' => [
                        'asc' => ['user.full_name' => SORT_ASC],
                        'desc' => ['user.full_name' => SORT_DESC],
                    ],
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'timesheet_id' => $this->timesheet_id,
            'team_id' => $this->team_id,
            'process_id' => $this->process_id,
            'work_time' => $this->work_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like','process.process_name',$this->getAttribute('process.process_name')])
              ->andFilterWhere(['like','team.team_name',$this->getAttribute('team.team_name')])
              ->andFilterWhere(['like','timesheet.date',$this->getAttribute('timesheet.date')])
              ->andFilterWhere(['like','timesheet.point',$this->getAttribute('timesheet.point')])
              ->andFilterWhere(['like','user.full_name',$this->getAttribute('user.full_name')]);

        return $dataProvider;
    }
}
