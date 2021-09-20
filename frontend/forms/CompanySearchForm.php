<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;

class CompanySearchForm extends Model
{
    public $text;
    public $region_id;

    public function __construct($config = [])
    {
        /** @var \frontend\components\DomainUrlManager $urlManager */
        $urlManager = Yii::$app->getUrlManager();
        $region = $urlManager->region;
        if (!empty($region)) {
            $this->region_id = $region->id;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['text'], 'string'],
            [['region_id'], 'integer'],
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }
}
