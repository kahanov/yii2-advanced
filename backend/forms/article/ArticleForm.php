<?php

namespace backend\forms\article;

use common\models\article\Article;
use common\models\article\ArticleCategory;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ArticleForm extends Model
{
    public $name;
    public $category_id;
    public $slug;
    public $status;
    public $title;
    public $description;
    public $content;
    public $photo;
    public $anons;

    private $_article;

    /**
     * ArticleForm constructor.
     * @param Article|null $article
     * @param array $config
     */
    public function __construct(Article $article = null, $config = [])
    {
        if ($article) {
            $this->name = $article->name;
            $this->category_id = $article->category_id;
            $this->slug = $article->slug;
            $this->status = $article->status;
            $this->title = $article->title;
            $this->description = $article->description;
            $this->content = $article->content;
            $this->photo = $article->photo;
            $this->anons = $article->anons;

            $this->_article = $article;
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'slug', 'category_id', 'status', 'anons', 'content'], 'required'],
            [['category_id'], 'integer'],
            [['status'], 'boolean'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['name', 'slug'], 'unique', 'targetClass' => Article::class, 'filter' => $this->_article ? ['<>', 'id', $this->_article->id] : null],
            [['description', 'content', 'anons'], 'string'],
            [['photo'], 'image'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('common', 'Идентификатор'),
            'category_id' => Yii::t('common', 'Категория'),
            'name' => Yii::t('common', 'Название'),
            'slug' => Yii::t('common', 'Название транслитом'),
            'status' => Yii::t('common', 'Статус'),
            'created_at' => Yii::t('common', 'Дата создания'),
            'updated_at' => Yii::t('common', 'Дата обновления'),
            'title' => Yii::t('backend/common', 'SEO title'),
            'description' => Yii::t('backend/common', 'SEO description'),
            'content' => Yii::t('backend/common', 'Содержание'),
            'photo' => Yii::t('backend/common', 'Фото'),
            'anons' => Yii::t('backend/common', 'Анонс'),
        ];
    }

    /**
     * @return array
     */
    public function categoriesList(): array
    {
        return ArrayHelper::map(ArticleCategory::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }
}
