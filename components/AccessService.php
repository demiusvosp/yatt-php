<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:37
 */

namespace app\components;

use Yii;
use yii\base\Component;
use yii\rbac\CheckAccessInterface;
use yii\rbac\ManagerInterface;
use yii\rbac\Item;
use yii\rbac\Role;
use app\helpers\Access;
use app\models\entities\Project;


/**
 * Class AccessService
 *
 * @property ProjectService $projectService
 */
class AccessService extends Component implements CheckAccessInterface
{
    /** @var  ProjectService */
    //public $projectService = null; будут получать только те кому надо, в момент когда надо
    /** @var  ManagerInterface */
    public $authManager;


    public function init()
    {
        parent::init();
        $this->authManager = Yii::$app->get('authManager');
    }


    /**
     * Проверить разрешение на действие, в том числе действие в рамках проекта
     *
     * @param int|string $userId
     * @param string     $permissionName
     * @param array      $params
     * @return bool
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $projectService = Yii::$app->get('projectService');
        if ($projectService->project) {
            $permissionName = Access::projectItem($permissionName, $projectService->project);
        }
        Yii::trace('checkAccess to ' . $permissionName, 'access');

        return $this->authManager->checkAccess($userId, $permissionName, $params);
    }


    /**
     * Создать и настроить роли и полномочия для проекта.
     *
     * @param $project
     */
    public function createProjectAccesses($project)
    {
        $root = $this->authManager->getRole(Access::ROOT);

        $admin = $this->addRole(
            Access::projectItem(Access::ADMIN, $project),
            [$root]
        );
        $employee = $this->addRole(
            Access::projectItem(Access::EMPLOYEE, $project),
            [$admin]
        );
        $view = $this->addRole(
            Access::projectItem(Access::VIEW, $project),
            [$employee]
        );

        $this->addPermission(
            Access::projectItem(Access::PROJECT_SETTINGS, $project),
            [$admin]
        );
        $this->addPermission(
            Access::projectItem(Access::OPEN_TASK, $project),
            [$employee]
        );
        $this->addPermission(
            Access::projectItem(Access::EDIT_TASK, $project),
            [$employee]
        );
        $this->addPermission(
            Access::projectItem(Access::CLOSE_TASK, $project),
            [$employee] // пока будем считать, что работник может закрывать задачи. (но потом в админке это можно будет выключить)
        );
    }


    /**
     * Содать и добавить роль.
     *
     * @param        $roleName
     * @param Item[] $parents - те, кто наследуют права роли
     * @return \yii\rbac\Role
     */
    public function addRole($roleName, $parents = [])
    {
        $role = $this->authManager->createRole($roleName);
        $this->authManager->add($role);
        foreach ($parents as $parent) {
            $this->authManager->addChild($parent, $role);
        }

        return $role;
    }


    /**
     * Создать и добавить полномочие
     *
     * @param        $permissionName
     * @param Item[] $parents - те, кто наследуют права полномочия
     * @return \yii\rbac\Permission
     */
    public function addPermission($permissionName, $parents = [])
    {
        $permission = $this->authManager->createPermission($permissionName);
        $this->authManager->add($permission);
        foreach ($parents as $parent) {
            $this->authManager->addChild($parent, $permission);
        }

        return $permission;
    }


    /**
     * Назначить пользователю указнный доступ
     *
     * @param string|Role  $role
     * @param int          $userId
     * @param Project|null $project
     * @return \yii\rbac\Assignment
     */
    public function assign($role, $userId, $project = null)
    {
        if (is_string($role)) {
            $role = $this->authManager->getRole(Access::projectItem($role, $project));
        }
        if (!$role && !($role instanceof Role)) {
            throw new \InvalidArgumentException('argument is not a Role');
        }

        return $this->authManager->assign($role, $userId);
    }


    /**
     * Отзвать у пользователя указанный доступ
     *
     * @param string|Role  $role
     * @param int          $userId
     * @param Project|null $project
     * @return bool
     */
    public function revoke($role, $userId, $project = null)
    {
        if (is_string($role)) {
            $role = $this->authManager->getRole(Access::projectItem($role, $project));
        }
        if (!$role && !($role instanceof Role)) {
            throw new \InvalidArgumentException('argument is not a Role');
        }

        return $this->authManager->revoke($role, $userId);
    }

}
