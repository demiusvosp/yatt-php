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
    /** Количество юзеров которое мы разрешаем выгружать за раз */
    const FIND_USER_LIMIT = 5;


    public function andStatus($status = User::STATUS_ACTIVE)
    {
        return $this->andWhere([User::tableName().'.status' => $status]);
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


    /**
     * Найти пользователей по имени или email
     * @param string $search
     * @param int    $limit
     * @return mixed
     */
    public static function findUserByNameMail($search = '', $limit = UserQuery::FIND_USER_LIMIT)
    {
        $query = (new static(User::className()))
            ->select(['id', 'username', 'email'])
            ->andStatus()
            ->limit($limit)
            ->asArray();
        if(empty($search)) {
            // пока так, но вобще надо хранить активность юзера, и сортировать по ней.
            $query->orderBy(['updated_at' => 'desc']);
        } else {
            $query->where(['or', ['like', 'username', $search], ['like', 'email', $search]]);
        }

        return $query->all();
    }

}
