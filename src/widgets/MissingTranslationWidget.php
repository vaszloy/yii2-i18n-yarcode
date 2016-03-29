<?php 

namespace yarcode\i18n\widgets;

use Yii;
use yii\base\Widget;
use yarcode\i18n\components\I18N;

class MissingTranslationWidget extends Widget
{

    /** @var $accessRole role to widget access */
    public $accessRole;

    /** @var $missingTranslations */
    public $missingTranslations;

    /** @var $url */
    public $url;

	public function run()
	{
        if ($this->existMissingTranslations()) {
            return $this->render('missingTranslationWidget', [
                'url' => $this->url,
                'languages' => Yii::$app->i18n->languages,
                'missingTranslations' => $this->missingTranslations,
            ]);
        }
	}

    public function setMissingTranslations()
    {
        $this->missingTranslations = Yii::$app->cache->get(I18N::MISSING_TRANSLATIONS_KEY);
        Yii::$app->cache->delete(I18N::MISSING_TRANSLATIONS_KEY);
        return $this->missingTranslations;
    }

    private function existMissingTranslations()
    {
        return $this->setMissingTranslations() && $this->url && Yii::$app->user->can($this->accessRole);
    }

}