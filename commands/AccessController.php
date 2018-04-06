<?php
/**
 * User: demius
 * Date: 23.03.17
 * Time: 0:37
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use app\components\auth\IAccessItem;
use app\components\auth\AuthProjectManager;
use app\components\auth\Role;
use app\models\entities\Project;
use yii\rbac\Item;


/**
 * Команды обслуживания системы полномочий
 *
 */
class AccessController extends Controller
{
    /** @var AuthProjectManager $authManager */
    protected $authManager;


    public function init()
    {
        $this->authManager = Yii::$app->get('authManager');

        parent::init();
    }


    /**
     * @param string $actionID
     * @return array
     */
    public function options($actionID)
    {
        $options = [];// global

        return ArrayHelper::merge(
            $options,
            parent::options($actionID)
        );
    }

//    public function optionAliases()
//    {
//        return ArrayHelper::merge(
//            [
//                't' => 'outTree',
//            ],
//            parent::optionAliases()
//        );
//    }


    /**
     * Получить текущий список полномочий
     *
     * @param string|integer $projectId - Проект
     * @param string         $view      - Вид списка list | tree | list-all
     * @return int
     */
    public function actionIndex($projectId = null, $view = 'list')
    {
        $project = Project::findOne($projectId);
        if ($project) {
            $this->stdout("\nProject: " . $project->name . "\n");
        }

        if ($view == 'tree') {
            // хз какая роль главная в проекте, идем от рута
            $root = $this->authManager->getRole(Role::ROOT);

            $this->buildAccessTree($root, $project);

            return ExitCode::OK;
        }

        // list или list-all
        if ($project) {
            $items = $this->authManager->getRolesByProject(
                $project,
                ($view == 'list-all') ? null : Item::TYPE_ROLE
            );
        } else {
            $items = $this->authManager->getRoles();
            if ($view == 'list-all') {
                $items = ArrayHelper::merge(
                    $items,
                    $this->authManager->getPermissions()
                );
            }
        }
        // отсортируем
        ArrayHelper::multisort($items, ['type', 'name'], SORT_ASC);

        foreach ($items as $item) {
            if ($item instanceof Role) {
                $this->stdout("[Role] ", Console::BOLD);
            } elseif ($view == 'list-all') {
                $this->stdout("[Permission] ", Console::BOLD);
            }
            $this->stdout($item->name . ' - ' . $item->description . ",\n");

        }

        return ExitCode::OK;
    }


    /**
     * Обновить список полномочий проекта
     *
     * @param $projectId - Проект
     * @return int
     */
    public function actionRefreshPermissions($projectId)
    {
        $project = Project::findOne($projectId);
        if (!$project) {
            $this->stdout("Project not found.\n", Console::BOLD, Console::FG_RED);

            return ExitCode::DATAERR;
        }
        $this->stdout("Project: " . $project->name . " processing:\n", Console::BOLD);

        $result = $this->authManager->refreshProjectAccesses($project);

        $this->stdout("end.\n added:\n");
        foreach ($result as $item) {
            if($item instanceof Item) {
                $this->stdout($item->name . " added.\n");
            } else {
                $this->stdout($item . "\n", Console::FG_RED, Console::BOLD);
            }
        }

        return ExitCode::OK;
    }


    /**
     * Построить дерево полномочий.
     * @param Item   $item
     * @param Project $project
     * @param int     $level
     */
    protected function buildAccessTree($item, $project, $level = 0)
    {
        $childs = $this->authManager->getChildren($item->name);
        ArrayHelper::multisort($childs, ['type', 'name'], SORT_ASC);
        /** @var IAccessItem $child */
        foreach ($childs as $child) {
            if ($project && $project->suffix != $child->getProject()) {
                // если строим дерево только для одного проекта
                continue;
            }
            $indent = str_repeat('    ', $level);

            if ($child instanceof Role) {
                $this->stdout($indent . "[Role] ", Console::BOLD);
            } else {
                $this->stdout($indent . "[Permission] ", Console::BOLD);
            }
            $this->stdout($child->name . ' - ' . $child->description . "\n");
            $this->buildAccessTree($child, $project, $level + 1);
        }
    }
}
