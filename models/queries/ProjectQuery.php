<?php

namespace app\models\queries;

use app\models\entities\Project;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\entities\Project]].
 *
 * @see \app\models\entities\Project
 */
class ProjectQuery extends ActiveQuery
{
    public function andPublic($publicStatus = Project::STATUS_PUBLIC_ALL)
    {
        return $this->andWhere(['public' => $publicStatus]);
    }

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