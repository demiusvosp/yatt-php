<?php

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\entities\Project]].
 *
 * @see \app\models\entities\Project
 */
class ProjectQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\entities\Project[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\entities\Project|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
