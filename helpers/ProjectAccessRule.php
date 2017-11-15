<?php
/**
 * User: demius
 * Date: 15.11.17
 * Time: 23:24
 */

namespace app\helpers;

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
            return false;
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
        if ($this->project->public == Project::STATUS_PUBLIC_REGISTED
            && Yii::$app->user->can(Access::VIEW)
        ) {
            // в этот проект могут только те, кому явно дан доступ
            return true;
        }

        return false;
    }
}