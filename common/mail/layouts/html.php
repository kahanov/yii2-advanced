<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

/** @var \frontend\components\DomainUrlManager $urlManager */
$urlManager = Yii::$app->frontendUrlManager;
$urlManager->baseUrl = Yii::$app->params['frontendHostInfo'];
$homeUrl = $urlManager->createAbsoluteUrl(['/site/index']);
$adAddUrl = $urlManager->createAbsoluteUrl(['/account/ad/create']);
$accountUrl = $urlManager->createAbsoluteUrl(['/account']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div style="-ms-text-size-adjust:100%;-webkit-font-smoothing:subpixel-antialiased;-webkit-text-size-adjust:100%;margin:0;background-color:#f4f4f4;color:#000;font-family:helvetica,arial;font-size:13px;line-height:18px;table-layout:fixed;text-align:center;">
    <table cellpadding="0" align="center" style="border-collapse:collapse;margin:0 auto;max-width:600px;width:100%;">
        <tbody>
        <tr>
            <td style="-webkit-font-smoothing:subpixel-antialiased;margin:0;background-color:#fff;border-bottom:#f4f4f4 1px solid;color:#000;font-family:helvetica,arial;font-size:0;line-height:18px;padding-bottom:20px;text-align:left">
                <div style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;display:inline-block;font-family:helvetica,arial;font-size:14px;line-height:18px;max-width:249px;text-align:left;vertical-align:top;width:100%">
                    <table width="100%" cellpadding="20" style="border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td valign="top"
                                style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;font-family:helvetica,arial;font-size:11px;line-height:18px;padding:10px 20px 0 30px;text-align:left">
                                <a href="<?= $homeUrl ?>" style="color:#39f!important;text-decoration:underline"
                                   target="_blank" rel=" noopener noreferrer">
                                    <img src="<?= $homeUrl ?>image/logo.png" width="40" height="40" style="border:0">
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;display:inline-block;font-family:helvetica,arial;font-size:14px;line-height:18px;max-width:349px;text-align:left;vertical-align:top;width:100%">
                    <table width="100%" cellpadding="20" style="border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;font-family:helvetica,arial;font-size:11px;line-height:18px;padding:25px 0 0 20px;text-align:left">
                                <b>
                                    <a href="<?= $adAddUrl ?>" target="_blank"
                                       style="color:#39f!important;text-decoration:underline" rel="noopener noreferrer">
                                        <span style="color:#39f">+ РАЗМЕСТИТЬ ОБЪЯВЛЕНИЕ</span>
                                    </a>
                                </b>
                            </td>
                            <td style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;font-family:helvetica,arial;font-size:11px;line-height:18px;padding:25px 0 0 20px;text-align:left">
                                <b>
                                    <a href="<?= $accountUrl ?>" target="_blank"
                                       style="color:#39f!important;text-decoration:underline"
                                       rel=" noopener noreferrer">
                                        <span style="color:#39f">ЛИЧНЫЙ КАБИНЕТ</span>
                                    </a>
                                </b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td style="-webkit-font-smoothing:subpixel-antialiased;margin:0;background-color:#fff;color:#000;font-family:helvetica,arial;font-size:0;line-height:18px;text-align:left!important">
                <div style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;display:inline-block;font-family:helvetica,arial;font-size:14px;line-height:18px;max-width:580px;text-align:left;vertical-align:top;width:100%">
                    <table width="100%" cellpadding="30" style="border-collapse:collapse">
                        <tbody>
                        <tr>
                            <td style="-webkit-font-smoothing:subpixel-antialiased;margin:0;border-bottom:#f4f4f4 1px solid;color:#000;font-family:helvetica,arial;font-size:13px;line-height:18px;text-align:left">
                                <?= $content ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;font-family:helvetica,arial;font-size:0;line-height:18px;padding-top:10px;text-align:left!important">
                <div style="-webkit-font-smoothing:subpixel-antialiased;margin:0;color:#000;display:inline-block;font-family:helvetica,arial;font-size:14px;line-height:18px;max-width:580px;text-align:left;vertical-align:top;width:100%">
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
