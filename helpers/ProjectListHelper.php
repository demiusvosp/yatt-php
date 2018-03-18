<?php
/**
 * User: demius
 * Date: 18.03.18
 * Time: 22:16
 */

namespace app\helpers;

use Yii;
use app\models\queries\ProjectQuery;
use app\models\entities\Project;

/**
 * Class ProjectListHelper - различные функции списков проектов меню, главная и т.д.
 *
 * @package app\helpers
 */
class ProjectListHelper
{

    public static function ProjectsMainMenu()
    {
        $projectMenu = [];
        $allProjects = ProjectQuery::allowProjectsList();

        if (count($allProjects) == 0) {
            $projectMenu[] = ['label' => Yii::t('common', 'Home'), 'url' => ['main/index']];

        } elseif (count($allProjects) == 1) {
            $project = $allProjects[0];
            $projectMenu[] = [
                'label' => $project->name,
                'url' => ProjectUrl::to(['project/overview', 'project' => $project])
            ];

        } else {
            $projectItems = [];
            foreach ($allProjects as $project) {
                $projectItems[] = [
                    'label' => $project->name,
                    'url' => ProjectUrl::to(['project/overview', 'project' => $project])
                ];
            }
            $projectItems[] = '<li class="divider"></li>';
            $projectItems[] = ['label' => Yii::t('common', 'Home'), 'url' => ['main/index']]; //вобще это скорее страница все проекты

            $projectMenu[] = [
                'label' => Yii::t('project', 'Projects'),
                'url' => ['main/index'],
                'items' => $projectItems];
        }

        return $projectMenu;
    }

}
