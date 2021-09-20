<?php

namespace backend\forms\settings\params;

use common\models\Config;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

class ParamsSearch extends Model
{
    public $name;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Config::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
