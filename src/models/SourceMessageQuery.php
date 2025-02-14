<?php

namespace vaszloy\i18n\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * Class SourceMessageQuery
 * @package vaszloy\i18n\models
 */
class SourceMessageQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function notTranslated()
    {
        $messageTableName = Message::tableName();
        $query = Message::find()->select($messageTableName . '.id');
        $i = 0;
        foreach (Yii::$app->getI18n()->languages as $language) {
            if ($i === 0) {
                $query->andWhere($messageTableName . '.language = :language and ' . $messageTableName . '.translation is not null',
                    [':language' => $language]);
            } else {
                $query->innerJoin($messageTableName . ' t' . $i
                    ,
                    't' . $i . '.id = ' . $messageTableName . '.id and t' . $i . '.language = :language and t' . $i . '.translation is not null',
                    [':language' => $language]);
            }
            $i++;
        }
        $ids = $query->indexBy('id')->all();
        $this->andWhere(['not in', 'id', array_keys($ids)]);
        return $this;
    }

    /**
     * @return $this
     */
    public function translated()
    {
        $messageTableName = Message::tableName();
        $query = Message::find()->select($messageTableName . '.id');
        $i = 0;
        foreach (Yii::$app->getI18n()->languages as $language) {
            if ($i === 0) {
                $query->andWhere($messageTableName . '.language = :language and ' . $messageTableName . '.translation is not null',
                    [':language' => $language]);
            } else {
                $query->innerJoin($messageTableName . ' t' . $i
                    ,
                    't' . $i . '.id = ' . $messageTableName . '.id and t' . $i . '.language = :language and t' . $i . '.translation is not null',
                    [':language' => $language]);
            }
            $i++;
        }
        $ids = $query->indexBy('id')->all();
        $this->andWhere(['in', 'id', array_keys($ids)]);
        return $this;
    }
}
