<?php

namespace app\modules\cars\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CarMarkSearch represents the model behind the search form of `app\modules\cars\models\CarMark`.
 */
class CarMarkSearch extends CarMark
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_popular', 'year_from', 'year_to', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_cyrillic', 'country'], 'safe'],
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
        $query = CarMark::find();

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
            'is_popular' => $this->is_popular,
            'year_from' => $this->year_from,
            'year_to' => $this->year_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_cyrillic', $this->name_cyrillic])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}

