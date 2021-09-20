<?php

namespace common\services\geo;

use common\models\geo\Street;
use common\helpers\BaseCommonHelper;
use common\models\geo\City;
use common\forms\GeoSearchForm;
use Yandex\Geo\Api;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\sphinx\MatchExpression;

class GeoService
{
    private $indexer;

    /**
     * GeoService constructor.
     * @param GeoIndexerService $indexer
     */
    public function __construct(GeoIndexerService $indexer)
    {
        $this->indexer = $indexer;
    }

    /**
     * Поиск адреса в sphinx
     * @param GeoSearchForm $form
     * @param bool $import
     * @param bool $yandex
     * @return array
     */
    public function searchAddressCreate(GeoSearchForm $form, $import = false, $yandex = false): array
    {
        $data = [];
        $country = $this->search('country', ['title' => $form->country]);
        if (empty($country[0])) {
            $country = $this->search('country', ['title' => $form->country], false);
        }
        if (!empty($country[0])) {
            $queryRegion = $form->region;
            if ($form->region == 'Москва') {
                $queryRegion = 'Московская область';
                if (empty($form->city)) {
                    $form->city = 'Москва';
                }
            }
            if (strpos($queryRegion, 'Республика') !== false) {
                $queryRegion = trim(str_replace('Республика', '', $queryRegion));
            }
            if (strpos($queryRegion, 'республика') !== false) {
                $queryRegion = trim(str_replace('республика', '', $queryRegion));
            }
            if (strpos($queryRegion, 'респ') !== false) {
                $queryRegion = trim(str_replace('респ', '', $queryRegion));
            }
            $query = [
                'country_id' => $country[0],
                'title' => $queryRegion,
            ];
            if (empty($queryRegion)) {
                if ($form->city == 'Москва') {
                    $region[0] = 1053480;
                }
                if ($form->city == 'Санкт-Петербург') {
                    $region[0] = 2;
                }
            } else {
                $region = $this->search('region', $query);
            }
            if (empty($region[0])) {
                $region = $this->search('region', $query, false);
            }
            if (!empty($region[0])) {
                if (!empty($form->district)) {
                    $queryDistrict = $form->district;

                    if ($queryRegion == 'Московская область') {
                        if (strpos($queryDistrict, 'городской округ') !== false) {
                            $queryDistrict = trim(str_replace('городской округ', '', $form->district));
                        }
                        if (strpos($queryDistrict, 'Городской округ') !== false) {
                            $queryDistrict = trim(str_replace('Городской округ', '', $form->district));
                        }
                        if ($queryDistrict == 'Ступино') {
                            $queryDistrict = 'Ступинский район';
                        }
                        if ($queryDistrict == 'Домодедово') {
                            $queryDistrict = 'Домодедовский район';
                        }
                    } else {
                        if (strpos($queryDistrict, 'городской округ') !== false) {
                            $queryDistrict = NULL;
                        }
                        if (strpos($queryDistrict, 'муниципальное образование') !== false) {
                            $queryDistrict = NULL;
                        }
                    }
                    if (strpos($queryDistrict, 'р-н') !== false) {
                        $queryDistrict = trim(str_replace('р-н', '', $queryDistrict));
                    }
                    if ($queryDistrict && !empty($form->city) && $form->city != $queryDistrict) {
                        $query = [
                            'region_id' => $region[0],
                            'title' => $queryDistrict
                        ];
                        $district = $this->search('district', $query);
                        if (empty($district[0])) {
                            $district = $this->search('district', $query, false);
                        }
                    }
                }
                $queryCity = explode(',', $form->city);
                $queryCity = $queryCity[0];
                if (strpos($queryCity, 'станица') !== false) {
                    $queryCity = trim(str_replace('станица', '', $queryCity));
                }
                if (strpos($queryCity, 'пос.') !== false) {
                    $queryCity = trim(str_replace('пос.', '', $queryCity));
                }
                if (strpos($queryCity, 'деревня') !== false) {
                    $queryCity = trim(str_replace('деревня', '', $queryCity));
                }
                if (strpos($queryCity, 'поселок городского типа') !== false) {
                    $queryCity = trim(str_replace('поселок городского типа', '', $queryCity));
                }
                if (strpos($queryCity, 'поселок городского типа') !== false) {
                    $queryCity = trim(str_replace('поселок городского типа', '', $queryCity));
                }
                if (strpos($queryCity, 'рабочий посёлок') !== false) {
                    $queryCity = trim(str_replace('рабочий посёлок', '', $queryCity));
                }
                if (strpos($queryCity, 'рабочий поселок') !== false) {
                    $queryCity = trim(str_replace('рабочий поселок', '', $queryCity));
                }
                if (strpos($queryCity, 'поселок') !== false) {
                    $queryCity = trim(str_replace('поселок', '', $queryCity));
                }
                if (strpos($queryCity, 'посёлок') !== false) {
                    $queryCity = trim(str_replace('посёлок', '', $queryCity));
                }
                if (strpos($queryCity, 'село ') !== false) {
                    $queryCity = trim(str_replace('село ', '', $queryCity));
                }
                $query = [
                    'country_id' => $country[0],
                    'region_id' => $region[0],
                    'title' => $queryCity
                ];
                if (!empty($district[0])) {
                    $query['district_id'] = $district[0];
                }
                if ($yandex && strpos($queryCity, 'садовое товарищество') !== false) {
                    $geoCity = City::find()->andWhere(['like', 'title', $queryCity])->asArray()->limit(1)->one();
                    if (empty($geoCity)) {
                        $city[0] = $this->addCity($queryCity, $queryRegion, $country[0], $region[0], (!empty($district[0])) ? $district[0] : NULL, []);
                    } else {
                        $city[0] = $geoCity['id'];
                    }
                }
                if (empty($city[0])) {
                    $city = $this->search('city, city_rt', $query);
                    if (empty($city[0])) {
                        $city = $this->search('city, city_rt', $query, false);
                    }
                    if (empty($city[0]) && !empty($query['district_id'])) {
                        unset($query['district_id']);
                        $city = $this->search('city, city_rt', $query);
                    }
                }
                if (empty($city[0])) {
                    $city = $this->search('city, city_rt', $query, false);
                }
                if (empty($city[0])) {
                    if ($import) {
                        $address = $form->country . ',' . $queryRegion;
                        if (!empty($queryDistrict) && !empty($district[0])) {
                            $address .= ',' . $queryDistrict;
                        }
                        $address .= ',' . $queryCity;
                        //$result = $this->getIsYandexCity($address);
                        $result = self::getIsOpenStreetMapCity($address);
                        if (!empty($result)) {
                            if (!empty($result['regionName']) && !empty($result['cityName'])) {
                                if ($result['regionName'] == 'Москва') {
                                    $result['regionName'] = 'Московская область';
                                }
                                if ($result['regionName'] == $queryRegion) {
                                    $city[0] = $this->addCity($result['cityName'], $queryRegion, $country[0], $region[0], (!empty($district[0])) ? $district[0] : NULL, $result['coordinates']);
                                }
                            }
                        }
                    } else {
                        $city[0] = $this->addCity($queryCity, $queryRegion, $country[0], $region[0], (!empty($district[0])) ? $district[0] : NULL);
                    }
                }
                if (!empty($city[0])) {
                    if (!empty($form->street)) {
                        /** @var Street $street */
                        $geoStreet = Street::find()->where(['city_id' => $city[0]])->andWhere(['name' => $form->street])->limit(1)->one();
                        if (!$geoStreet && $form->is_yandex == 1) {
                            $geoStreet = new Street();
                            $geoStreet->name = $form->street;
                            $geoStreet->city_id = $city[0];
                            $geoStreet->slug = BaseCommonHelper::slugify($form->street);
                            $geoStreet->save();
                        }
                    }
                    $data = [
                        'country_id' => $country[0],
                        'region_id' => !empty($region[0]) ? $region[0] : NULL,
                        'district_id' => !empty($district[0]) ? $district[0] : NULL,
                        'city_id' => $city[0],
                        'street' => $form->street,
                        'street_id' => (!empty($geoStreet)) ? $geoStreet->id : NULL,
                        'house_number' => $form->house_number,
                        'coordinates' => $form->coordinates,
                        'status' => 'suc',
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * @param GeoSearchForm $form
     * @return array
     */
    public function searchAddress(GeoSearchForm $form): array
    {
        $data = [];
        $country = $this->search('country', ['title' => $form->country]);
        if (empty($country[0])) {
            $country = $this->search('country', ['title' => $form->country], false);
        }
        if (!empty($country[0])) {
            if (!empty($form->region)) {
                $queryRegion = $form->region;
                if ($form->region == 'Москва') {
                    $queryRegion = 'Московская область';
                    if (empty($form->city)) {
                        $form->city = 'Москва';
                    }
                }
                if (strpos($queryRegion, 'Республика') !== false) {
                    $queryRegion = trim(str_replace('Республика', '', $queryRegion));
                }
                if (strpos($queryRegion, 'республика') !== false) {
                    $queryRegion = trim(str_replace('республика', '', $queryRegion));
                }
                if (strpos($queryRegion, 'респ') !== false) {
                    $queryRegion = trim(str_replace('респ', '', $queryRegion));
                }
                $query = [
                    'country_id' => $country[0],
                    'title' => $queryRegion,
                ];
                $region = $this->search('region', $query);
                if (empty($region[0])) {
                    $region = $this->search('region', $query, false);
                }

                if (!empty($region[0])) {
                    if (!empty($form->district)) {
                        $queryDistrict = $form->district;
                        if ($queryRegion == 'Московская область') {
                            if (strpos($queryDistrict, 'городской округ') !== false) {
                                $queryDistrict = trim(str_replace('городской округ', '', $form->district));
                            }
                            if (strpos($queryDistrict, 'Городской округ') !== false) {
                                $queryDistrict = trim(str_replace('Городской округ', '', $form->district));
                            }
                            if ($queryDistrict == 'Ступино') {
                                $queryDistrict = 'Ступинский район';
                            }
                            if ($queryDistrict == 'Домодедово') {
                                $queryDistrict = 'Домодедовский район';
                            }
                        } else {
                            if (strpos($queryDistrict, 'городской округ') !== false) {
                                $queryDistrict = NULL;
                            }
                            if (strpos($queryDistrict, 'муниципальное образование') !== false) {
                                $queryDistrict = NULL;
                            }
                        }
                        if (strpos($queryDistrict, 'р-н') !== false) {
                            $queryDistrict = trim(str_replace('р-н', '', $queryDistrict));
                        }
                        if ($queryDistrict && !empty($form->city) && $form->city != $queryDistrict) {
                            $query = [
                                'region_id' => $region[0],
                                'title' => $queryDistrict
                            ];
                            $district = $this->search('district', $query);
                            if (empty($district[0])) {
                                $district = $this->search('district', $query, false);
                            }
                        }
                    }
                    if (!empty($form->city)) {
                        $queryCity = explode(',', $form->city);
                        $queryCity = $queryCity[0];
                        if (strpos($queryCity, 'станица') !== false) {
                            $queryCity = trim(str_replace('станица', '', $queryCity));
                        }
                        if (strpos($queryCity, 'пос.') !== false) {
                            $queryCity = trim(str_replace('пос.', '', $queryCity));
                        }
                        if (strpos($queryCity, 'деревня') !== false) {
                            $queryCity = trim(str_replace('деревня', '', $queryCity));
                        }
                        if (strpos($queryCity, 'поселок городского типа') !== false) {
                            $queryCity = trim(str_replace('поселок городского типа', '', $queryCity));
                        }
                        if (strpos($queryCity, 'поселок городского типа') !== false) {
                            $queryCity = trim(str_replace('поселок городского типа', '', $queryCity));
                        }
                        if (strpos($queryCity, 'рабочий посёлок') !== false) {
                            $queryCity = trim(str_replace('рабочий посёлок', '', $queryCity));
                        }
                        if (strpos($queryCity, 'рабочий поселок') !== false) {
                            $queryCity = trim(str_replace('рабочий поселок', '', $queryCity));
                        }
                        if (strpos($queryCity, 'поселок') !== false) {
                            $queryCity = trim(str_replace('поселок', '', $queryCity));
                        }
                        if (strpos($queryCity, 'посёлок') !== false) {
                            $queryCity = trim(str_replace('посёлок', '', $queryCity));
                        }
                        if (strpos($queryCity, 'село ') !== false) {
                            $queryCity = trim(str_replace('село ', '', $queryCity));
                        }
                        $query = [
                            'country_id' => $country[0],
                            'region_id' => $region[0],
                            'title' => $queryCity
                        ];

                        if (!empty($district[0])) {
                            $query['district_id'] = $district[0];
                        }
                        $city = $this->search('city, city_rt', $query);
                        if (empty($city[0])) {
                            $city = $this->search('city, city_rt', $query, false);
                        }
                        if (empty($city[0])) {
                            unset($query['district_id']);
                            $city = $this->search('city, city_rt', $query);
                        }
                        if (empty($city[0])) {
                            $city = $this->search('city, city_rt', $query, false);
                        }
                        if (!empty($city[0]) && !empty($form->street)) {
                            /** @var Street $street */
                            $geoStreet = Street::find()->where(['city_id' => $city[0]])->andWhere(['name' => $form->street])->limit(1)->one();
                            if (!$geoStreet && $form->is_yandex == 1) {
                                $geoStreet = new Street();
                                $geoStreet->name = $form->street;
                                $geoStreet->city_id = $city[0];
                                $geoStreet->slug = BaseCommonHelper::slugify($form->street);
                                $geoStreet->save();
                            }
                        }
                    }
                }
            }
            $data = [
                'country_id' => $country[0],
                'region_id' => !empty($region[0]) ? $region[0] : NULL,
                'district_id' => !empty($district[0]) ? $district[0] : NULL,
                'city_id' => !empty($city[0]) ? $city[0] : NULL,
                'street' => $form->street,
                'street_id' => (!empty($geoStreet)) ? $geoStreet->id : NULL,
                'house_number' => $form->house_number,
                'coordinates' => $form->coordinates,
                'status' => 'suc',
            ];
        }

        return $data;
    }

    /**
     * @param string $index
     * @param array $conditions
     * @param bool $isExact
     * @return array
     */
    public function search(string $index, array $conditions, bool $isExact = true): array
    {
        try {
            $query = new \yii\sphinx\Query();
            $query->from($index)->select(["*"])->cache(3600);
            if (!empty($conditions['country_id'])) {
                $query->andWhere(['country_id' => $conditions['country_id']]);
            }

            if (!empty($conditions['region_id'])) {
                $query->andWhere(['region_id' => $conditions['region_id']]);
            }

            if (!empty($conditions['district_id'])) {
                $query->andWhere(['district_id' => $conditions['district_id']]);
            }

            if (!empty($conditions['city_id'])) {
                $query->andWhere(['city_id' => $conditions['city_id']]);
            }

            $title = Html::encode($conditions['title']);
            $title = ($isExact) ? $title : "*$title*";
            $query->match(
                (new MatchExpression())->match(['title' => $title])
            );

            $results = $query->limit(1)->all();

            return ArrayHelper::getColumn($results, 'id');
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);

            return [];
        }
    }

    /**
     * @param $address
     * @return array
     * @throws \Yandex\Geo\Exception
     * @throws \Yandex\Geo\Exception\CurlError
     * @throws \Yandex\Geo\Exception\ServerError
     */
    private function getIsYandexCity($address): array
    {
        $data = [];
        $limit = self::getCacheYandexLimit();
        if ($limit >= 20000) {
            return $data;
        }
        $api = new Api();
        $api
            ->setLimit(1)// кол-во результатов
            ->setLang(Api::LANG_RU)// локаль ответа
            ->setQuery($address)
            ->setToken(Yii::$app->params['yandexApiKey'])
            ->load();
        $response = $api->getResponse();
        $found = $response->getFoundCount();
        if ($found > 0) {
            self::setCacheYandexLimit();
            // Список найденных точек
            $collections = $response->getList();
            if (!empty($collections)) {
                $collection = $collections[0];
                $latitude = $collection->getLatitude(); // широта для исходного запроса
                $longitude = $collection->getLongitude(); // долгота для исходного запроса
                $localityName = $collection->getLocalityName();
                if (!empty($localityName)) {
                    $regionName = $collection->getAdministrativeAreaName();
                    $data = [
                        'cityName' => $localityName,
                        'regionName' => $regionName,
                        'coordinates' => [$latitude, $longitude],
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * @param $address
     * @return array
     */
    public static function getIsOpenStreetMapCity($address): array
    {
        $data = [];
        try {
            $client = new \yii\httpclient\Client();
            /*$userAgent = '';
            $referer = '';
            $headers = Yii::$app->request->headers;
            if (!empty($headers)) {
                if ($headers->has('User-Agent')) {
                    $userAgent = $headers->get('user-agent');
                }
                if ($headers->has('referer')) {
                    $referer = $headers->get('referer');
                }
            }*/

            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('https://nominatim.openstreetmap.org/search')
                ->setData([
                    'q' => $address,
                    'format' => 'geojson',
                    'addressdetails' => 1,
                    'limit' => 1,
                ])
                ->addHeaders([
                    'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36',
                    'referer' => '',
                ])
                ->send();

            $result = $response->data;
            if (!empty($result) && !empty($result['features'])) {
                $result = $result['features'][0];
                $address = $result['properties']['address'];
                $regionName = $address['state'];
                $cityName = NULL;
                $street = (!empty($address['road'])) ? $address['road'] : NULL;
                $houseNumber = (!empty($address['house_number'])) ? $address['house_number'] : NULL;
                if (!empty($address['village'])) {
                    $cityName = $address['village'];
                } else {
                    if (!empty($address['town'])) {
                        $cityName = $address['town'];
                    } else {
                        if (!empty($address['city'])) {
                            $cityName = $address['city'];
                        }
                    }
                }
                $coordinates = $result['geometry']['coordinates'];
                $latitude = $coordinates[1]; // широта для исходного запроса
                $longitude = $coordinates[0]; // долгота для исходного запроса
                $data = [
                    'cityName' => $cityName,
                    'regionName' => $regionName,
                    'street' => $street,
                    'houseNumber' => $houseNumber,
                    'coordinates' => [$latitude, $longitude],
                ];
            }
        } catch (\Exception $e) {}

        return $data;
    }

    /**
     * @param string $cityName
     * @param string $regionName
     * @param int $countryId
     * @param int $regionId
     * @param int|NULL $districtId
     * @param array $coordinates
     * @return int|null
     */
    private function addCity(string $cityName, string $regionName, int $countryId, int $regionId, int $districtId = NULL, array $coordinates = []): ?int
    {
        if ($cityName == $regionName) {
            return NULL;
        }
        $queryCity = City::find()->andWhere(['country_id' => $countryId, 'region_id' => $regionId])->andWhere(['like', 'title', $cityName]);
        if ($districtId) {
            $queryCity->andWhere(['district_id' => $districtId]);
        }
        $geoCity = $queryCity->limit(1)->one();
        if (!$geoCity && $districtId) {
            $geoCity = City::find()->andWhere(['country_id' => $countryId, 'region_id' => $regionId])->andWhere(['like', 'title', $cityName])->limit(1)->one();
        }
        if (!$geoCity) {
            $geoCity = new City();
            $geoCity->title = $cityName;
            $geoCity->country_id = $countryId;
            $geoCity->region_id = $regionId;
            $geoCity->district_id = $districtId;
            if (!empty($coordinates)) {
                $geoCity->coordinate_x = $coordinates[0];
                $geoCity->coordinate_y = $coordinates[1];
            }
            $geoCity->slug = BaseCommonHelper::slugify($geoCity->title);
            $geoCity->save();
            $geoCity->slug = $geoCity->slug . '-' . $geoCity->id;
            $geoCity->save();
            $data = [
                'id' => $geoCity->id,
                'title' => $geoCity->title,
                'country_id' => $geoCity->country_id,
                'region_id' => $geoCity->region_id,
                'district_id' => $geoCity->district_id,
                'coordinate_x' => $geoCity->coordinate_x,
                'coordinate_y' => $geoCity->coordinate_y,
            ];
            $this->indexer->index('city_rt', $data);
        }
        return $geoCity->id;
    }

    /**
     * Запись в кеш первого обращения в сутки к yandex geocode
     */
    private static function setNewCacheYandexLimit(): void
    {
        $key = 'yandex-limit';
        $cache = \Yii::$app->cache;
        $term = date('Y-m-d H:i:s', strtotime('+1days', time()));
        $data = [
            'limit' => 1,
            'term' => strtotime($term),
        ];
        $cache->set($key, $data);
    }

    /**
     * Изменение кеша подсчета количества обращений к yandex geocode
     */
    public static function setCacheYandexLimit(): void
    {
        $key = 'yandex-limit';
        $cache = \Yii::$app->cache;
        $yandexLimit = $cache->get($key);
        if ($yandexLimit === false) {
            self::setNewCacheYandexLimit();
        } else {
            $term = $yandexLimit['term'];
            if (time() >= $term) {
                self::setNewCacheYandexLimit();
            } else {
                $yandexLimit['limit']++;
                $cache->set($key, $yandexLimit);
            }
        }
    }

    /**
     * Получение количества обращений к yandex geocode
     * @return int
     */
    public static function getCacheYandexLimit(): int
    {
        $key = 'yandex-limit';
        $cache = \Yii::$app->cache;
        $yandexLimit = $cache->get($key);
        $limit = 0;
        if ($yandexLimit !== false) {
            $limit = $yandexLimit['limit'];
        }
        return $limit;
    }

    /**
     * @param string $address
     * @return array
     * @throws \Yandex\Geo\Exception
     * @throws \Yandex\Geo\Exception\CurlError
     * @throws \Yandex\Geo\Exception\ServerError
     */
    public static function getCoordinates(string $address): array
    {
        $coordinates = [];
        $api = new Api();
        $api
            ->setLimit(1)// кол-во результатов
            ->setLang(Api::LANG_RU)// локаль ответа
            ->setQuery($address)
            ->setToken(Yii::$app->params['yandexApiKey'])
            ->load();
        $response = $api->getResponse();
        $found = $response->getFoundCount();
        if ($found > 0) {
            GeoService::setCacheYandexLimit();
            // Список найденных точек
            $collections = $response->getList();
            if (!empty($collections)) {
                $collection = $collections[0];
                $latitude = $collection->getLatitude(); // широта для исходного запроса
                $longitude = $collection->getLongitude(); // долгота для исходного запроса
                $coordinates = $latitude . ',' . $longitude;
                $coordinates = explode(',', $coordinates);
            }
        }
        return $coordinates;
    }
}
