<?php

namespace vaszloy\i18n\widgets;

use Yii;

/**
 * Description of SelectLanguageWidget
 *
 * @author ValentinK <rlng-krsk@yandex.ru>
 */
class SelectLanguageWidget extends \yii\base\Widget
{
    /** @var string $viewFile you can specify your own view file */
    public $viewFile = 'selectLanguageWidget';

    /** @var string $selectedLanguage by default */
    public $selectedLanguage;

    /** @var array $languages to select */
    public $languages = [];

    /** @var mixed $formAction where your i18n component change current user language */
    public $formAction = null;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (!$this->formAction) {
            throw new \yii\base\InvalidConfigException("You must specify setLanguage action for this widget.");
        }

        if (empty($this->languages)) {
            $this->languages = Yii::$app->i18n->languages;
        }

        if (!$this->selectedLanguage) {
            $this->selectedLanguage = Yii::$app->i18n->getLanguage();
        }

        \yii\helpers\Url::remember();
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->viewFile, [
            'languages' => $this->languages,
            'selectedLanguage' => $this->selectedLanguage,
            'formAction' => $this->formAction
        ]);
    }
}
