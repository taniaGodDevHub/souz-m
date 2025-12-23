<?php

namespace app\modules\insurance_companies\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * InsuranceCompanySearch represents the model behind the search form of `app\modules\insurance_companies\models\InsuranceCompany`.
 */
class InsuranceCompanySearch extends InsuranceCompany
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['full_name', 'short_name', 'previous_name', 'license_number', 'license_date', 'rsa_certificate_number', 'rsa_certificate_date', 'phone_fax', 'email', 'website', 'address', 'inn'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = InsuranceCompany::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['full_name' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'license_date' => $this->license_date,
            'rsa_certificate_date' => $this->rsa_certificate_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'previous_name', $this->previous_name])
            ->andFilterWhere(['like', 'license_number', $this->license_number])
            ->andFilterWhere(['like', 'rsa_certificate_number', $this->rsa_certificate_number])
            ->andFilterWhere(['like', 'phone_fax', $this->phone_fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'inn', $this->inn]);

        return $dataProvider;
    }
}

