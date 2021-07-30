<?php

namespace yarcode\i18n\components;

use yarcode\i18n\models\SourceMessage;
use Yii;
use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;
use yii\i18n\MissingTranslationEvent;

/**
 * Class I18N
 * @package yarcode\i18n\components
 */
class I18N extends \yii\i18n\I18N
{
    const MISSING_TRANSLATIONS_KEY = 'missingTranslations';
    const EXISTING_TRANSLATIONS_KEY = 'existingTranslations';

    /** @var string */
    public $sourceMessageTable = '{{%source_message}}';

    /** @var string */
    public $messageTable = '{{%message}}';

    /**
     * Array of supported languages in format:
     * en-EN => English
     * @var array
     */
    public $languages;

    /** @var array */
    public $missingTranslationHandler = ['\yarcode\i18n\components\I18N', 'missingTranslation'];

    /**
     * In some cases we don't need to set Application Language automatically on component ::init()
     * @var bool
     */
    public $autoSetLanguage = true;

    /**
     * Allows getting the preferred language
     * @var bool
     */
    public $allowPreferredLanguage = true;

    /** @var string $defaultLanguage a default user language, default to getLanguage() method */
    public $defaultLanguage;

    /** @var string */
    public $languageSessionKey = 'language';

    /** @var string */
    public $languageParam = 'language';

    /** @var string $_language a reference to the language set */
    private $_language;

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
        $sourceMessage = SourceMessage::find()
            ->where('category = :category and message = :message', [
                ':category' => $event->category,
                ':message' => $event->message
            ])
            ->with('messages')
            ->one();

        if (!$sourceMessage) {
            $sourceMessage = new SourceMessage;
            $sourceMessage->setAttributes([
                'category' => $event->category,
                'message' => $event->message
            ], false);
            $sourceMessage->save(false);
        }

        $sourceMessage->initMessages();
        $sourceMessage->saveMessages();
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->languages) {
            throw new InvalidConfigException('You should configure i18n component [language]');
        }

        if (empty($this->defaultLanguage)) {
            $this->defaultLanguage = Yii::$app->language;
        }

        if (Yii::$app instanceof yii\console\Application) {
            $this->_language = $this->defaultLanguage;
        }

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }

        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }

        if ($this->autoSetLanguage) {
            $this->setLanguage();
        }

        parent::init();
    }

    /**
     * Returns current appliation language, if it's not set automatically detects and then returns
     *
     * @return array|mixed|string
     */
    public function getLanguage()
    {
        return $this->_language = $this->detectLanguage();
    }

    /**
     * Set
     *
     * @param null $language
     * @return array|mixed|null|string
     */
    public function setLanguage($language = null)
    {
        if ($language === null) {
            $language = $this->detectLanguage();
        }

        if (!array_key_exists($language, $this->languages)) {
            throw new InvalidParamException(Yii::t("app", "Invalid language param"));
        }

        $this->_language = $language;

        if (Yii::$app->has('session')) {
            Yii::$app->session->set($this->languageSessionKey, $language);
        }

        Yii::$app->language = $language;

        return $language;
    }

    /**
     * Detects and returns current application language
     *
     * @return array|mixed|string
     */
    public function detectLanguage()
    {
        if ($this->_language !== null) {
            return $this->_language;
        } elseif (Yii::$app->has('session') && Yii::$app->session->has($this->languageSessionKey)) {
            $language = Yii::$app->session->get($this->languageSessionKey);
        } elseif (Yii::$app->has('request') && Yii::$app->request->post($this->languageParam)) {
            $language = Yii::$app->request->post($this->languageParam);
        } elseif (Yii::$app->has('request') && Yii::$app->request->get($this->languageParam)) {
            $language = Yii::$app->request->get($this->languageParam);
        } elseif ($this->allowPreferredLanguage === true) {
            $language = Yii::$app->request->getPreferredLanguage(array_keys($this->languages));
        } else {
            $language = $this->defaultLanguage;
        }

        if (!array_key_exists($language, $this->languages)) {
            $language = $this->defaultLanguage;
        }

        return $language;
    }
}
