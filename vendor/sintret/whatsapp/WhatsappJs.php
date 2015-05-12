<?php

/**
 * @link https://github.com/sintret/yii2-chat-adminlte
 * @copyright Copyright (c) 2014 Andy fitria 
 * @license MIT
 */

namespace sintret\whatsapp;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Andy Fitria <sintret@gmail.com>
 */
class WhatsappJs extends AssetBundle {

    public $sourcePath = '@vendor/sintret/WhatsAPI/assets';
    public $js = [
        'js/whatsapp.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
