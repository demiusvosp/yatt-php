<?php
/**
 * User: demius
 * Date: 15.11.17
 * Time: 23:24
 */

namespace app\components\auth;

use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessRule;
use app\models\entities\Project;


class ProjectAccessRule extends AccessRule
{
    /** @var  Project */
    public $project;


    public function allows($action, $user, $request)
    {
        $this->roleParams = ['project' => $this->project];

        return parent::allows($action, $user, $request);
    }
}