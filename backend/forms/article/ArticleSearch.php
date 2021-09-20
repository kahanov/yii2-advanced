<?php

namespace backend\forms\article;

use common\models\article\Article;
use common\models\article\ArticleCategory;
use common\helpers\ArticleHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ArticleSearch extends Model
{
    public $id;
    public $name;
    public $status;
    public $category_id;
	
	/**
	 * @return array
	 */
	public function rules(): array
    {
        return [
            [['id', 'status', 'category_id',], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Article::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
	
	/**
	 * @return array
	 */
	public function categoriesList(): array
    {
        return ArrayHelper::map(ArticleCategory::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }
	
	/**
	 * @return array
	 */
	public function statusList(): array
    {
        return ArticleHelper::statusList();
    }
}
