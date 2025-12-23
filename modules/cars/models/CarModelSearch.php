<?php

namespace app\modules\cars\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CarModelSearch represents the model behind the search form of `app\modules\cars\models\CarModel`.
 */
class CarModelSearch extends CarModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mark_id', 'year_from', 'year_to', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_cyrillic', 'class'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = CarModel::find()->with('mark');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'mark_id' => $this->mark_id,
            'year_from' => $this->year_from,
            'year_to' => $this->year_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_cyrillic', $this->name_cyrillic])
            ->andFilterWhere(['like', 'class', $this->class]);

        return $dataProvider;
    }
}

