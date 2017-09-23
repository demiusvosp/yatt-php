<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 23.09.17
 * Time: 19:33
 */

namespace app\models\queries;

use app\models\entities\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    public function andStatus($status = User::STATUS_ACTIVE)
    {
        return $this->andWhere(['status' => $status]);
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