<?php

namespace yarcode\i18n;

use yarcode\i18n\commands\I18nCommand;
use Yii;
use yii\base\BootstrapInterface;
use yii\data\Pagination;

/**
 * Class Bootstrap
 * @package yarcode\i18n
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application && $i18nModule = Yii::$app->getModule('i18n')) {
            $moduleId = $i18nModule->id;
            $app->getUrlManager()->addRules([
                'translation/<id:\d+>' => $moduleId . '/default/update',
                'translation/mass-update' => $moduleId . '/default/mass-update',
                'translation' => $moduleId . '/default/index',
            ], false);

            Yii::$container->set(Pagination::className(), [
                'pageSizeLimit' => [1, 100],
                'defaultPageSize' => $i18nModule->pageSize
            ]);
        }

        if ($app instanceof \yii\console\Application) {
            if (!isset($app->controllerMap['i18n'])) {
                $app->controllerMap['i18n'] = I18nCommand::className();
            }
        }
    }
}
