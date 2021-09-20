<?php

namespace frontend\components;

use common\models\geo\City;
use common\models\geo\Country;
use common\models\geo\Region;
use Yii;
use yii\web\UrlManager;
use Pdp\Rules;
use yii\web\NotFoundHttpException;

class DomainUrlManager extends UrlManager
{
    public $type = 'dev'; // Заменить на prod если на рабочем сервере
    public $scheme = 'http://';
    public $hostInfo;
    public $baseDomains = [];
    public $subDomainKey = 'subDomain';
    public $subDomain = NULL;
    public $domain = NULL;
    public $country;
    public $region;
    public $city;
    public $street;
    public $district;
    public $mainCity;

    /**
     * Переопределение url
     * @param array|string $params
     * @param null $scheme
     * @return string
     */
    public function createAbsoluteUrl($params, $scheme = null)
    {
        $domain = $this->setDomain($params);
        $subDomain = $this->setSubDomain($params);
        if (!empty($subDomain)) {
            $this->setHostInfo($this->scheme . $subDomain . '.' . $domain);
            if (isset($params['isConsole'])) {
                unset($params['isConsole']);
                $this->setBaseUrl($this->scheme . $subDomain . '.' . $domain);
            }
        } else {
            $this->setHostInfo($this->scheme . $domain);
            if (isset($params['isConsole'])) {
                unset($params['isConsole']);
                $this->setBaseUrl($this->scheme . $domain);
            }
        }

        return parent::createAbsoluteUrl($params, $scheme);
    }

    /**
     * @param array|string $params
     * @return string
     */
    public function createUrl($params)
    {
        return parent::createUrl($params);
    }

    /**
     * @param \yii\web\Request $request
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function parseRequest($request)
    {
        $this->hostInfo = $request->getHostInfo();
        $host = $request->getHostName();
        $domainManager = Yii::$app->cache->getOrSet('public_suffix_list', function ($cache) {
            return Rules::fromPath(Yii::getAlias('@frontend/web/public_suffix_list.dat'));
        }, 86400);
        $domainRules = $domainManager->resolve($host);
        $domain = $domainRules->registrableDomain()->toString();
        $subDomain = $domainRules->subDomain()->toString();
        if (empty($domain)) {
            $domain = Yii::$app->params['baseDomain'];
        }
        $this->getDomain($domain);
        $this->domain = $domain;
        if (!empty($subDomain)) {
            $this->getSubDomain($subDomain, $domain, $request->pathInfo);
            $this->subDomain = $subDomain;
        }

        $pathInfo = $request->pathInfo;
        $queryParams = $request->getQueryParams();

        $redirect = NULL;
        if (isset($queryParams['page']) && $queryParams['page'] == 1) {
            unset($queryParams['page']);
            if (!empty($this->subDomain)) {
                $redirect = $this->scheme . $this->subDomain . '.' . $domain . '/' . $pathInfo;
            } else {
                $redirect = $this->scheme . $domain . '/' . $pathInfo;
            }
            if (!empty($queryParams) && ($query = http_build_query($queryParams)) !== '') {
                $redirect .= '?' . $query;
            }
        }

        if ($redirect) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }

        $request->setQueryParams($queryParams);

        return parent::parseRequest($request);
    }

    /**
     * Определение домена и добавление параметров
     * @param string $domain
     * @throws NotFoundHttpException
     */
    private function getDomain(string $domain): void
    {
        if (!in_array($domain, $this->baseDomains)) {
            throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
        /*$ukDomain = Yii::$app->params['ukDomain'];
        $byDomain = Yii::$app->params['byDomain'];
        $kzDomain = Yii::$app->params['kzDomain'];
        switch ($domain) {
            case ($domain === 'mall.com.ua' || $domain === $ukDomain): //Украина
                $this->country = Country::find()->where(['id' => 2])->cache(86400)->one();
                break;
            case ($domain === 'mall.by' || $domain === $byDomain): //Беларусь
                $this->country = Country::find()->where(['id' => 3])->cache(86400)->one();
                break;
            case ($domain === 'mall.kz' || $domain === $kzDomain): //Казахстан
                $this->country = Country::find()->where(['id' => 4])->cache(86400)->one();
                break;
            default:
                $this->country = Country::find()->where(['id' => 1])->cache(86400)->one();
        }*/

        $this->country = Country::find()->where(['id' => 1])->cache(86400)->one();
        Yii::$app->params['cookieDomain'] = '.' . $domain;
        Yii::$app->language = $this->country->language;
    }

    /**
     * @param array $params
     * @return string
     */
    private function setDomain(array &$params): string
    {
        $baseDomain = Yii::$app->params['baseDomain'];
        /*$ukDomain = Yii::$app->params['ukDomain'];
        $byDomain = Yii::$app->params['byDomain'];
        $kzDomain = Yii::$app->params['kzDomain'];*/
        $domain = ($this->type == 'dev') ? $baseDomain : 'mall.ru';
        $country_id = (!empty($this->country)) ? $this->country->id : NULL;
        if (!empty($params['country_id'])) {
            $country_id = $params['country_id'];
            unset($params['country_id']);
        }
        if ($country_id) {
            /*switch ($country_id) {
                case 2: //Украина
                    $domain = ($this->type == 'dev') ? $ukDomain : 'mall.com.ua';
                    break;
                case 3: //Беларусь
                    $domain = ($this->type == 'dev') ? $byDomain : 'mall.by';
                    break;
                case 4: //Казахстан
                    $domain = ($this->type == 'dev') ? $kzDomain : 'mall.kz';
                    break;
                default:
                    $domain = ($this->type == 'dev') ? $baseDomain : 'mall.ru'; // Россия
            }*/

            $domain = ($this->type == 'dev') ? $baseDomain : 'mall.ru'; // Россия
        }

        return $domain;
    }

    /**
     * Определение поддомена
     * @param string $subDomain
     * @param string $domain
     * @param string $pathInfo
     * @throws NotFoundHttpException
     */
    private function getSubDomain(string $subDomain, string $domain, string $pathInfo)
    {
        $region = $this->getRegion($subDomain);
        if (!$region) {
            /** @var Region $region */
            $region = Region::find()->where(['country_id' => $this->country->id])->andWhere(['subdomain' => $subDomain])->cache(86400)->limit(1)->one();
            if ($region) {
                $url = $this->scheme . $region->subdomain . '.' . $domain . '/' . $pathInfo;

                return Yii::$app->response->redirect($url, 301)->send();
            } else {
                /** @var City $city */
                $city = City::find()->where(['slug' => $subDomain])->cache(86400)->limit(1)->one();
                if ($city) {
                    $region = Region::find()->where(['id' => $city->region_id])->cache(86400)->limit(1)->one();
                    if ($region) {
                        $url = $this->scheme . $region->subdomain . '.' . $domain . '/' . $pathInfo;
                        return Yii::$app->response->redirect($url, 301)->send();
                    }
                } else {
                    $cities = City::find()->alias('gc')->
                    select([
                        'gc.*',
                        '(select count(*) from ad a where a.city_id = gc.id) as countAd'
                    ])->
                    where(['like', 'gc.slug', $subDomain . '%', false])->orderBy(['gc.district_id' => SORT_ASC])->limit(10)->
                    asArray()->cache(86400)->all();
                    $city = NULL;
                    if (!empty($cities)) {
                        usort($cities, function ($a, $b) {
                            return ($b['countAd'] - $a['countAd']);
                        });
                        $city = $cities[0];
                    }
                    if ($city) {
                        $region = Region::find()->where(['id' => $city['region_id']])->cache(86400)->limit(1)->one();
                        if ($region) {
                            $url = $this->scheme . $region->subdomain . '.' . $domain . '/';
                            $isCategory = false;
                            if (preg_match('#^rent/(.*[a-z])$#is', $pathInfo, $matches)) {
                                $isCategory = true;
                            }
                            if (preg_match('#^sale/(.*[a-z])$#is', $pathInfo, $matches)) {
                                $isCategory = true;
                            }
                            if ($isCategory) {
                                $url .= $city['slug'] . '/';
                            }
                            $url .= $pathInfo;

                            return Yii::$app->response->redirect($url, 301)->send();
                        }
                    }
                }
            }

            $url = $this->scheme . $domain . '/' . $pathInfo;

            return Yii::$app->response->redirect($url, 301)->send();
            //throw new NotFoundHttpException(Yii::t('common', 'Запрашиваемая страница не существует'));
        }
    }

    /**
     * @param array $params
     * @return string
     */
    private function setSubDomain(array &$params): string
    {
        $subDomain = '';
        if (isset($params['region_id']) && $params['region_id'] == 0) {
            unset($params['region_id']);
            return $subDomain;
        }
        if (!empty($params['region_id'])) {
            /** @var Region $region */
            $region = Region::find()->where(['id' => intval($params['region_id'])])->cache(86400)->one();
            if ($region) {
                $subDomain = $region->subdomain;
            }
            unset($params['region_id']);
        } elseif (!empty($this->region)) {
            if (!empty($params['country_id'])) {
                if ($params['country_id'] == $this->region->country_id) {
                    $subDomain = $this->region->subdomain;
                } else {
                    $subDomain = $this->mainCity->subdomain;
                }
            } else {
                $subDomain = $this->region->subdomain;
            }
        }

        return $subDomain;
    }

    /**
     * @param string $subDomain
     * @return string
     */
    private function getRegion(string $subDomain): string
    {
        /** @var Region $region */
        $region = Region::find()->where(['country_id' => $this->country->id, 'subdomain' => $subDomain])->cache(86400)->limit(1)->one();
        if ($region) {
            $this->region = $region;
            $this->mainCity = City::find()->where(['country_id' => $this->country->id, 'region_id' => $region->id, 'main_city_region' => 1])->cache(86400)->limit(1)->one();

            return true;
        }

        return false;
    }
}
