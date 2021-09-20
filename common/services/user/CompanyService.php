<?php

namespace common\services\user;

use common\models\user\Company;
use frontend\components\DomainUrlManager;
use frontend\forms\CompanySearchForm;
use frontend\forms\MessageForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\data\Sort;
use yii\mail\MailerInterface;

class CompanyService
{
    private $mailer;

    /**
     * CompanyService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Поиск компаний в бд
     * @param CompanySearchForm $form
     * @return ActiveDataProvider
     */
    public function search(CompanySearchForm $form): ActiveDataProvider
    {
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'pageSizeLimit' => [10, 100],
            'validatePage' => false,
            'forcePageParam' => false,
        ]);
        $sort = new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'id',
                'name',
            ],
        ]);
        $query = Company::find()->cache(10800);
        //$query->andWhere(['not', ['logotype' => NULL]]);
        //$query->andWhere(['not', ['logotype' => '']]);
        if (!empty($form->region_id)) {
            $query->andWhere(['region_id' => $form->region_id]);
        }

        if (!empty($form->text)) {
            $query->andWhere(['or', ['like', 'description', $form->text], ['like', 'name', $form->text]]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'totalCount' => $query->count(),
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getHomeList(): ActiveDataProvider
    {
        $query = Company::find()->cache(10800);
        /** @var DomainUrlManager $urlManager */
        $urlManager = Yii::$app->getUrlManager();
        if (!empty($region = $urlManager->region)) {
            $regionId = $region->id;
            $query->andWhere(['region_id' => $regionId]);
        }
        $query->andWhere(['not', ['logotype' => NULL]]);
        $query->andWhere(['not', ['logotype' => '']]);
        $query->orderBy(['id' => SORT_DESC]);
        $query->limit(5);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => false,
        ]);
    }

    /**
     * @param MessageForm $form
     * @return bool
     */
    public function sendMessage(MessageForm $form): bool
    {
        $companyId = $form->company;
        $company = $this->get($companyId);
        $userTo = $company->user;
        $lastName = (!empty($userTo->last_name)) ? $userTo->last_name : NULL;
        $firstName = (!empty($userTo->first_name)) ? $userTo->first_name : NULL;
        $userToName = ($lastName && $firstName) ? $firstName . ' ' . $lastName : $userTo->username;
        $data = [
            'message' => $form->message,
            'name' => $form->name,
            'email' => $form->email,
            'phone' => $form->phone,
            'userToName' => $userToName,
        ];
        $setToEmail = $userTo->email;
        if (!empty($company->contact_email)) {
            $setToEmail = $company->contact_email;
        }
        $sent = $this
            ->mailer
            ->compose(
                ['html' => 'message/html', 'text' => 'message/text'],
                ['data' => $data]
            )
            ->setTo($setToEmail)
            ->setSubject(Yii::t('common', 'Вам поступило сообщение с сайта') . ' ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            return false;
        }

        return true;
    }

    /**
     * @param $id
     * @return Company
     */
    public function get($id): Company
    {
        if (!$realtor = Company::findOne($id)) {
            throw new \DomainException(Yii::t('common', 'Данные не найдены'));
        }
        return $realtor;
    }
}
