<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:37
 */

namespace app\components;

use Yii;
use yii\rbac\CheckAccessInterface;
use yii\rbac\DbManager;
use yii\rbac\Assignment;
use yii\db\Query;
use yii\rbac\Item;
use yii\rbac\Role as BaseRole;
use app\components\access\Role;
use app\components\access\Permission;
use app\helpers\Access;
use app\models\entities\Project;
use app\models\entities\User as EntityUser;


/**
 * Class AccessManager
 *
 * @property ProjectService $projectService
 */
class AccessManager extends DbManager implements CheckAccessInterface
{
    /** @var  ProjectService */
    //public $projectService = null; будут получать только те кому надо, в момент когда надо


    public function init()
    {
        parent::init();
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
        Yii::info('checkAccess ' . $userId . ' to ' . $permissionName, 'access');

        return parent::checkAccess($userId, $permissionName, $params);
    }


    /**
     * Создать роль.
     *
     * @inheritdoc
     */
    public function createRole($name)
    {
        $role = new Role();
        $role->name = $name;

        return $role;
    }


    /**
     * Создать и добавить роль.
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
        $role = $this->createRole($roleName);
        $role->isProject = $project;
        $role->label = $label;

        $this->add($role);
        Yii::info('add Role ' . $role->name, 'access');
        foreach ($parents as $parent) {
            $this->addChild($parent, $role);
        }

        return $role;
    }


    /**
     * @inheritdoc
     */
    public function createPermission($name)
    {
        $permission = new Permission();
        $permission->name = $name;

        return $permission;
    }


    /**
     * Создать и добавить полномочие
     *
     * @param         $permissionName
     * @param Item[]  $parents - те, кто наследуют права полномочия
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
        $permission = $this->createPermission($permissionName);
        $permission->isProject = $project;
        $permission->label = $label;

        $this->add($permission);
        Yii::info('add Permission ' . $permission->name, 'access');
        foreach ($parents as $parent) {
            $this->addChild($parent, $permission);
        }

        return $permission;
    }


    /**
     * Назначить пользователю указнный доступ
     *
     * @param string|Role         $role
     * @param int                 $userId
     * @param Project|string|null $project
     * @return \yii\rbac\Assignment
     */
    public function assign($role, $userId, $project = null)
    {
        $assignment = new Assignment([
            'userId'    => $userId,
            'createdAt' => time(),
        ]);

        if (is_string($role)) {
            $assignment->roleName = $role;

        } else {
            if ($role instanceof BaseRole) {
                $assignment->roleName = $role->name;

            } else {
                throw new \InvalidArgumentException('argument is not a Role');
            }
        }
        // реальное название роли проекта состоит из имени роли и суффикса проекта
        if (Access::isProjectItem($assignment->roleName) && $project) {
            $assignment->roleName = Access::projectItem($assignment->roleName, $project);
        }

        Yii::info('assign ' . $assignment->roleName . ' to ' . $assignment->userId, 'access');
        $this->db->createCommand()
            ->insert($this->assignmentTable, [
                'user_id'    => $assignment->userId,
                'item_name'  => $assignment->roleName,
                'created_at' => $assignment->createdAt,
            ])->execute();

        return $assignment;
    }


    /**
     * Отзвать у пользователя указанный доступ
     *
     * @param string|Role         $role
     * @param int                 $userId
     * @param Project|string|null $project
     * @return bool
     */
    public function revoke($role, $userId, $project = null)
    {
        if (empty($userId)) {
            return false;
        }

        if (is_string($role)) {
            $roleName = $role;

        } else {
            if ($role instanceof BaseRole) {
                $roleName = $role->name;

            } else {
                throw new \InvalidArgumentException('argument is not a Role');
            }
        }
        // реальное название роли проекта состоит из имени роли и суффикса проекта
        if ($project) {
            $roleName = Access::projectItem($roleName, $project);
        }


        Yii::info('revoke ' . $roleName . ' from ' . $userId, 'access');

        return $this->db->createCommand()
                ->delete($this->assignmentTable, ['user_id' => (string)$userId, 'item_name' => $roleName])
                ->execute() > 0;
    }


    /**
     * Populates an auth item with the data fetched from database
     * Переопределяем из yii\rbac\DbManager для инстанцирования своих классов ролей и полномочий
     *
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row)
    {
        $class = $row['type'] == Item::TYPE_PERMISSION ? Permission::className() : Role::className();

        if (!isset($row['data']) || ($data = @unserialize(is_resource($row['data']) ? stream_get_contents($row['data']) : $row['data'])) === false) {
            $data = null;
        }

        return new $class([
            'name'        => $row['name'],
            'type'        => $row['type'],
            'description' => $row['description'],
            'ruleName'    => $row['rule_name'],
            'data'        => $data,
            'createdAt'   => $row['created_at'],
            'updatedAt'   => $row['updated_at'],
        ]);
    }


    /**
     * Получить роли проекта.
     *
     * @param Project|string $project - проект или его суффикс
     * @return array
     */
    public function getRolesByProject($project)
    {
        if (is_string($project)) {
            $suffix = $project;
        } else {
            if ($project instanceof Project) {
                $suffix = $project->suffix;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        $rbacRoles = (new Query())
            ->from($this->itemTable)
            ->andwhere(['=', 'type', Role::TYPE_ROLE])
            ->andWhere(['like', 'name', '_' . $suffix])
            ->all();

        $roles = [];
        foreach ($rbacRoles as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }

        return $roles;
    }


    /**
     * @inheritdoc
     */
    public function getRolesByUser($userId)
    {
        if (!isset($userId) || $userId === '') {
            return [];
        }
        if ($userId instanceof EntityUser) {
            $userId = $userId->id;
        }

        $query = (new Query)->select('b.*')
            ->from(['a' => $this->assignmentTable, 'b' => $this->itemTable])
            ->where('{{a}}.[[item_name]]={{b}}.[[name]]')
            ->andWhere(['a.user_id' => (string)$userId])
            ->andWhere(['b.type' => Item::TYPE_ROLE]);

        $roles = $this->getDefaultRoleInstances();
        foreach ($query->all($this->db) as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }

        return $roles;
    }


    /**
     * аналог ManagerInterface::getUsersByRole(), но возвращающий сущности, а не id'ы
     * @param $roleName
     * @return Project[]|array
     */
    public function getUsersByRole($roleName)
    {
        if (empty($roleName)) {
            return [];
        }

        return EntityUser::find()
            ->leftJoin($this->assignmentTable, $this->assignmentTable . '.user_id = user.id')
            ->andStatus()
            ->andWhere([$this->assignmentTable . '.item_name' => $roleName])
            ->all();
    }
}
