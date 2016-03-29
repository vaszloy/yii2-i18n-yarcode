<?php

namespace yarcode\i18n\backend;

use Yii;
use yii\base\Module as YiiModule;

class Module extends YiiModule
{

    public $pageSize = 50;

    public $prefix;

    public $controllerNamespace = 'yarcode\i18n\backend\controllers';

    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('yarcode/i18n', $message, $params, $language);
    }

}