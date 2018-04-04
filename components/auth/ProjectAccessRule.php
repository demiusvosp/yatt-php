<?php
/**
 * User: demius
 * Date: 15.11.17
 * Time: 23:24
 */

namespace app\components\auth;

use Yii;
use yii\filters\AccessRule;
use app\models\entities\Project;


class ProjectAccessRule extends AccessRule
{
    /** @var  Project */
    public $project;


    public function allows($action, $user, $request)
    {
        if (!parent::allows($action, $user, $request)) {

            // запретили, например по роли или методу
            return null;
        }

        if ($this->project->public == Project::STATUS_PUBLIC_ALL) {
            // в этот проект могут ходить все
            return true;
        }
        if ($this->project->public == Project::STATUS_PUBLIC_AUTHED
            && !Yii::$app->user->isGuest
        ) {
            // в этот проект могут заходить все залогиненные
            return true;
        }
        //@TODO заменить public на rbac

        return null;
    }
}