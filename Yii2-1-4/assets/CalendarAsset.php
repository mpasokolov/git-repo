<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 22/10/2018
 * Time: 19:46
 */

namespace app\assets;

use yii\web\AssetBundle;

class CalendarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/calendar.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}