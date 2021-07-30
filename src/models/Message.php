<?php

namespace vaszloy\i18n\models;

use vaszloy\i18n\backend\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class Message
 * @package vaszloy\i18n\models
 */
class Message extends ActiveRecord
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->messageTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            ['id', 'integer'],
            ['language', 'string', 'max' => 16],
            ['translation', 'string'],
            ['translation', 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'language' => Module::t('Language'),
            'translation' => Module::t('Translation')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }

}
