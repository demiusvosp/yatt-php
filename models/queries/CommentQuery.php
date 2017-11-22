<?php

namespace app\models\queries;

use yii\db\ActiveRecord;


/**
 * This is the ActiveQuery class for [[\app\models\entities\Comment]].
 *
 * @see \app\models\entities\Comment
 */
class CommentQuery extends \yii\db\ActiveQuery
{
    /**
     * Получить тред коментов (в будующем тут бы пагинацию из коробки поддержать)
     *
     * @param ActiveRecord $object
     * @param null         $db
     * @return \app\models\entities\Comment[]|array
     */
    public function getThread($object, $db = null)
    {
        return $this
            ->andWhere([
                'object_class' => $object->className(),
                'object_id'    => $object->id,
            ])
            ->all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
