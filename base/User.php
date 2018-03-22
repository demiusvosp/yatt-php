<?php
/**
 * User: demius
 * Date: 29.10.17
 * Time: 13:16
 */

namespace app\base;

use app\components\auth\Accesses;
use app\helpers\ProjectHelper;
use Yii;


class User extends \yii\web\User
{
    public function init()
    {
        parent::init();
        $this->accessChecker = Yii::$app->get('authManager');
    }


    /**
     * @param string $permissionName
     * @param array|string $params
     * @param bool   $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        $project = null;
        if(is_array($params) && isset($params['project'])) {
            $project = $params['project'];
        }
        if(is_string($params)) {
            $project = $params;
        }

        // может проверяют в текущем проекте
        if(!$project) {
            $project = ProjectHelper::currentProject();
        }
        $permissionName = Accesses::projectItem($permissionName, $project);

        return parent::can($permissionName, $params, $allowCaching);
    }

}