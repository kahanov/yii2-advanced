<?php

namespace frontend\urls;

use common\models\article\Article;
use common\models\article\ArticleCategory;
use yii\base\BaseObject;
use yii\web\UrlRuleInterface;
use yii\base\InvalidParamException;
use Yii;

class ArticleUrlRule extends BaseObject implements UrlRuleInterface
{
    public $prefix = 'article';

    /**
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     * @return array|bool
     */
    public function parseRequest($manager, $request)
    {
        if (preg_match('#^' . $this->prefix . '/(.*[a-z])$#is', $request->pathInfo, $matches)) {
            $path = $matches['1'];
            $pathChunks = $this->getPathSlug($path);

            if (!empty($pathChunks[1])) {
                $articleId = $this->getSlugArticle($pathChunks[1]);
                if ($articleId) {
                    return ['article/view', ['id' => $articleId]];
                }
                return false;
            }

            $categoryId = $this->getSlugCategory($pathChunks[0]);
            if ($categoryId) {
                return ['article/index', ['category_id' => $categoryId]];
            }
        }

        return false;
    }

    /**
     * @param string $slug
     * @return bool|mixed
     */
    private function getSlugCategory(string $slug)
    {
        /** @var ArticleCategory $category */
        $category = ArticleCategory::find()->where(['slug' => $slug])->limit(1)->cache(0)->one();
        if ($category) {
            return $category->id;
        }

        return false;
    }

    /**
     * @param string $slug
     * @return bool|int
     */
    private function getSlugArticle(string $slug)
    {
        /** @var Article $article */
        $article = Article::find()->where(['slug' => $slug])->limit(1)->cache(0)->one();
        if ($article) {
            return $article->id;
        }

        return false;
    }


    /**
     * @param \yii\web\UrlManager $manager
     * @param string $route
     * @param array $params
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        $url = '';
        if ($route == 'article/index') {
            if (!empty($params['category_id'])) {
                $category = ArticleCategory::find()->where(['id' => $params['category_id']])->cache(0)->one();
                if ($category) {
                    unset($params['category_id']);
                    $url .= $this->prefix . '/' . $category->slug;
                }
            }
        }

        if ($route == 'article/view') {
            if (empty($params['id'])) {
                throw new InvalidParamException('Empty id.');
            }
            $article = Article::find()->where(['id' => $params['id']])->cache(0)->one();
            if ($article) {
                unset($params['id']);
                $category = ArticleCategory::find()->where(['id' => $article->category_id])->cache(0)->one();
                $url .= $this->prefix . '/' . $category->slug . '/' . $article->slug;
            }
        }

        if (!empty($url)) {
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }
            return $url;
        }

        return false;
    }

    /**
     * @param $path
     * @return array
     */
    private function getPathSlug($path): array
    {
        $chunks = explode('/', $path);

        return $chunks;
    }
}
