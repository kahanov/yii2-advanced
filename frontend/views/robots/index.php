<?php

/* @var $this yii\web\View */

$file = $_SERVER['SCRIPT_FILENAME'];
$lastModifiedTime = filemtime($file);
$etag = $lastModifiedTime;
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModifiedTime) . " GMT");
header("Etag: $etag");
if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModifiedTime || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)) {
    header("HTTP/1.1 304 Not Modified");
} else {
    header("Content-Type: text/plain");
    $lines = [
        'User-agent: *',
        'Disallow: /*utm',
        'Disallow: /*clid=',
        'Disallow: /*openstat',
        'Disallow: /*webalizer',
        'Disallow: /*from',
        'Disallow: /*.pdf',
        'Disallow: /*.xls',
        'Disallow: /*.doc',
        'Disallow: /*.ppt',
        'Disallow: /account/',
        'Disallow: /auth/',
        'Disallow: /login',
        'Disallow: /signup',
        'Disallow: /reset',
        'Disallow: /index.php?',
        'Disallow: /index.php',
        'Disallow: /account*',
        'Disallow: *?*',
    ];
    /** @var \frontend\components\DomainUrlManager $urlManager */
    $urlManager = Yii::$app->getUrlManager();
    if (!empty($urlManager->region)) {
        $lines[] = 'Disallow: /article/';
        $sitemap = 'Sitemap: ' . $urlManager->scheme . $urlManager->subDomain . '.' . $urlManager->domain . '/sitemap.xml';
        //$lines[] = '';
        //$lines[] = 'Host: ' . $urlManager->scheme . $urlManager->subDomain . '.' . $urlManager->domain;
        //$lines[] = 'Sitemap: ' . $urlManager->scheme . $urlManager->subDomain . '.' . $urlManager->domain . '/sitemap.xml';
    } else {
        $lines[] = 'Disallow: /s-*';
        $lines[] = 'Disallow: /sitemap-ad-*';
        $lines[] = 'Disallow: /sitemap-ads-*';
        $lines[] = 'Disallow: /sitemap-agencies-*';
        $lines[] = 'Disallow: /sitemap-realtors-*';
        //$lines[] = '';
        //$lines[] = 'Host: ' . $urlManager->scheme . $urlManager->domain;
        //$lines[] = 'Sitemap: ' . $urlManager->scheme . $urlManager->domain . '/sitemap.xml';
        $sitemap = 'Sitemap: ' . $urlManager->scheme . $urlManager->domain . '/sitemap.xml';
    }
    $lines[] = '';
    $lines[] = '';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: Googlebot';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: Yandex';
    echo implode("\r\n", $lines);

    $lines = [
        'User-agent: PetalBot',
        'Disallow: /',
        '',
        '',
    ];
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: SemrushBot-SA';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: SemrushBot-BA';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: SemrushBot-SI';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: SemrushBot-SWA';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: SemrushBot-CT';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: SemrushBot-BM';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: AhrefsBot';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: grapeshot';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: serpstatbot';
    echo implode("\r\n", $lines);

    $lines[0] = 'User-agent: BLEXBot';

    array_pop($lines);
    $lines[] = $sitemap;

    echo implode("\r\n", $lines);
}
die;
