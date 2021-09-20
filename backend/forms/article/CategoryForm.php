<?php

namespace backend\forms\article;

use common\models\article\ArticleCategory;
use Yii;
use yii\base\Model;

class CategoryForm extends Model
{
    public $name;
    public $slug;
    public $sort = 255;
    public $title;
    public $description;

    private $_category;

    public function __construct(ArticleCategory $category = null, $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->sort = $category->sort;
            $this->title = $category->title;
            $this->description = $category->description;

            $this->_category = $category;
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'slug', 'sort'], 'required'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['name', 'slug'], 'unique', 'targetClass' => ArticleCategory::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
            [['description'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('common', 'Идентификатор'),
            'name' => Yii::t('common', 'Название'),
            'slug' => Yii::t('common', 'Название транслитом'),
            'sort' => Yii::t('backend/common', 'Порядковый номер'),
            'title' => Yii::t('backend/common', 'SEO title'),
            'description' => Yii::t('backend/common', 'SEO description'),
        ];
    }
}
