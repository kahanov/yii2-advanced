<?php
/* @var $this yii\web\View */

/* @var $model \frontend\forms\account\profile\UserProfileForm */

/* @var $user \common\models\user\User */

use yii\helpers\Html;

$this->title = Yii::t('frontend/account', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index page">
    <div class="page__content">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php switch ($model->profile_type):
            case 2:
                echo $this->render('company', [
                    'model' => $model,
                    'user' => $user,
                    'company' => (isset($company)) ? $company : NULL,
                ]);
                break;
            default:
                echo $this->render('profile', [
                    'model' => $model,
                    'user' => $user,
                    'company' => ($company) ? $company : NULL,
                ]);
        endswitch; ?>
    </div>
</div>
