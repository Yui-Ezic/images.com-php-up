<?php

namespace frontend\modules\user\assets;

use yii\web\AssetBundle;

/**
 * Description of FormAsset
 *
 * @author misha
 */
class FormAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'user/css/bootstrap.min.css',
        'user/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        'user/fonts/Linearicons-Free-v1.0.0/icon-font.min.css',
        'user/css/animate.css',
        'user/css/hamburgers.min.css',
        'user/css/animsition.min.css',
        'user/css/select2.min.css',
        'user/css/daterangepicker.css',
        'user/css/util.css',
        'user/css/main.css',
    ];
    public $js = [
        'user/js/animsition.min.js',
        'user/js/popper.js',
        'user/js/bootstrap.min.js',
        'user/js/select2.min.js',
        'user/js/moment.min.js',
        'user/js/daterangepicker.js',
        'user/js/countdowntime.js',
        'user/js/main.js',
    ];
}
