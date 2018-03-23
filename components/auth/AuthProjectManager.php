<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:37
 */

namespace app\components\auth;

use Yii;
use yii\db\Query;
use yii\rbac\CheckAccessInterface;
use yii\rbac\DbManager;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\Role as BaseRole;
use app\helpers\ProjectHelper;
use app\models\entities\Project;
use app\models\entities\User as EntityUser;


/**
 * Class AuthProjectManager
 *
 */
class AuthProjectManager extends DbManager implements CheckAccessInterface
{

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
        // вопрос, должен ли checkAccess знать о currentProject?
        if (ProjectHelper::currentProject()) {
            $permissionName = Accesses::projectItem($permissionName, ProjectHelper::currentProject());
        }

        $result = parent::checkAccess($userId, $permissionName, $params);
        Yii::info('checkAccess ' . $userId . ' to ' . $permissionName, 'access = ' . $result);
        return $result;
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
     * @inheritdoc
     */
    public function createPermission($name)
    {
        $permission = new Permission();
        $permission->name = $name;

        return $permission;
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
        $label = Accesses::itemLabels()[$roleName];// перед projectItem, т.к. ам нужно абстрактное название, без проекта

        $roleName = Accesses::projectItem($roleName, $project);
        if ($project) {
            $label = $project->name . ': ' . $label;
        }

        $role = $this->createRole($roleName);
        $role->isProject = $project;
        $role->label = $label;

        if(!$this->add($role)) {
            Yii::error("error in add role " . $role . " to project");
            return null;
        }

        Yii::info('add Role ' . $role->name, 'access');
        foreach ($parents as $parent) {
            $this->addChild($parent, $role);
        }

        return $role;
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
        $label = Accesses::itemLabels()[$permissionName];// перед projectItem, т.к. ам нужно абстрактное название, без проекта

        $permissionName = Accesses::projectItem($permissionName, $project);
        if ($project) {
            $label = $project->name . ': ' . $label;
        }

        $permission = $this->createPermission($permissionName);
        $permission->isProject = $project;
        $permission->label = $label;

        if(!$this->add($permission)) {
            Yii::error("error in add permission " . $permissionName . " to project");
            return null;
        }

        Yii::info('add permission ' . $permission->name, 'access');
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
            $assignment->roleName = Accesses::projectItem($role, $project);

        } else {
            if ($role instanceof BaseRole) {
                $assignment->roleName = Accesses::projectItem($role->name, $project);

            } else {
                throw new \InvalidArgumentException('argument is not a Role');
            }
        }
        // реальное название роли проекта состоит из имени роли и суффикса проекта
        $assignment->roleName = Accesses::projectItem($assignment->roleName, $project);

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
        $roleName = Accesses::projectItem($roleName, $project);


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
     * @param integer $itemType - тип доступа роль/полномочие
     * @return array
     */
    public function getRolesByProject($project, $itemType = Role::TYPE_ROLE)
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

        $query = (new Query())
            ->from($this->itemTable)
            ->andWhere(['like', 'name', '_' . $suffix]);
        if($itemType) {
            $query->andwhere(['=', 'type', Role::TYPE_ROLE]);
        }

        $roles = [];
        foreach ($query->all() as $row) {
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


    /**
     * @param Project $project
     */
    public function removeProjectAccesses($project)
    {
        // все элементы доступа проекта
        $names = (new Query())
            ->select(['name'])
            ->from($this->itemTable)
            ->where(['like', 'name', '_'.$project->suffix])
            ->column($this->db);
        if (empty($names)) {
            return;
        }

        // удалим назначения пользователям
        $this->db->createCommand()
            ->delete($this->assignmentTable, ['item_name' => $names])
            ->execute();

        // иерархия
        $this->db->createCommand()
            ->delete($this->itemChildTable, ['child' => $names])
            ->execute();

        // сами элементы доступа
        $this->db->createCommand()
            ->delete($this->itemTable, ['name' => $names])
            ->execute();

        $this->invalidateCache();
    }


    /**
     * Обновить полномочия проекта
     * @param Project $project
     * @return array[Permission|string] - Массив с добавленными полномочиями (или строками с ошибкой)
     */
    public function refreshProjectAccesses($project)
    {
        $existItems = $this->getRolesByProject($project, null);

        // нам нужен массив абстрактных полномочий, без проекта
        $existItems = array_map(
            function($item) { list($name, ) = explode('_', $item->name); return $name; },
            $existItems
        );
        $existItems = array_flip($existItems);

        $result = [];
        foreach (Accesses::projectItems() as $item) {
            if(isset($existItems[$item])) {
                // полномочие уже есть в проекте
                continue;
            }

            $res = $this->addPermission($item, [], $project);
            if($res) {
                $result[$item] = $res;
            } else {
                $result[$item] = 'Error in adding ' . $item;
            }
        }

        return $result;
    }
}
