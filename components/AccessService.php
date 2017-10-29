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
use yii\rbac\Role as BaseRole;
use app\components\access\Role;
use app\components\access\Permission;
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
        Yii::info('checkAccess '.$userId.' to '.$permissionName, 'access');

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
            Access::ADMIN,
            [$root],
            $project
        );
        $employee = $this->addRole(
            Access::EMPLOYEE,
            [$admin],
            $project
        );
        $view = $this->addRole(
            Access::VIEW,
            [$employee],
            $project
        );

        $this->addPermission(
            Access::PROJECT_SETTINGS,
            [$admin],
            $project
        );
        $this->addPermission(
            Access::OPEN_TASK,
            [$employee],
            $project
        );
        $this->addPermission(
            Access::EDIT_TASK,
            [$employee],
            $project
        );
        $this->addPermission(
            Access::CLOSE_TASK,
            [$employee],  // пока будем считать, что работник может закрывать задачи. (но потом в админке это можно будет выключить)
            $project
        );
    }


    /**
     * Содать и добавить роль.
     *
     * @param         $roleName
     * @param Item[]  $parents - те, кто наследуют права роли
     * @param Project $project - если указан, создается роль ассоциированная с проектом
     * @return \yii\rbac\Role
     */
    public function addRole($roleName, $parents = [], $project = null)
    {
        $label = Access::itemLabels()[$roleName];
        if ($project) {
            $roleName = Access::projectItem($roleName, $project);
            $label = $project->name . ': ' . $label;
        }
        $role = $this->authManager->createRole($roleName);
        $role->data = Role::setData(
            (bool) $project,
            $label
        );

        $this->authManager->add($role);
        Yii::info('add Role '.$role->name, 'access');
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
     * @param Project $project - если указан, создается полномочие ассоциированная с проектом
     * @return \yii\rbac\Permission
     */
    public function addPermission($permissionName, $parents = [], $project = null)
    {
        $label = Access::itemLabels()[$permissionName];
        if ($project) {
            $permissionName = Access::projectItem($permissionName, $project);
            $label = $project->name . ': ' . $label;
        }
        $permission = $this->authManager->createPermission($permissionName);
        $permission->data = Permission::setData(
            (bool)$project,
            $label
        );

        $this->authManager->add($permission);
        Yii::info('add Permission '.$permission->name, 'access');
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
        if (!$role && !($role instanceof BaseRole)) {
            throw new \InvalidArgumentException('argument is not a Role');
        }
        Yii::info('assign ' . $role->name . ' to ' . $userId,'access');

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
        if (!$role && !($role instanceof BaseRole)) {
            throw new \InvalidArgumentException('argument is not a Role');
        }
        Yii::info('revoke '.$role->name.' from '.$userId,'access');

        return $this->authManager->revoke($role, $userId);
    }


    /**
     * @param User $user
     * @return Role[]
     */
    public function getUserRoles($user)
    {
        $roles = [];
        foreach ($this->authManager->getRolesByUser($user->id) as $role) {
            $roles[] = new Role($role);
        }

        return $roles;
    }


    public function getAllRoles()
    {
        $roles = [];
        foreach ($this->authManager->getRoles() as $role) {
            $roles[] = new Role($role);
        }

        return $roles;
    }

}
