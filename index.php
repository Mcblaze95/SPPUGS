<?php

declare(strict_types=1);

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/backend/config/main.php',
    require __DIR__ . '/backend/config/main-local.php'
);

// This deployment boots the backend from the project root instead of
// backend/web, so Yii must publish assets relative to this entry script.
$scriptBaseUrl = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptBaseUrl = $scriptBaseUrl === '/' ? '' : rtrim($scriptBaseUrl, '/');
$config['components']['assetManager']['basePath'] = __DIR__ . '/assets';
$config['components']['assetManager']['baseUrl'] = $scriptBaseUrl . '/assets';

(new yii\web\Application($config))->run();
