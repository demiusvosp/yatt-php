<?php
/**
 * User: demius
 * Date: 28.10.17
 * Time: 0:37
 */

namespace app\components\auth;

use Yii;
use yii\base\InvalidArgumentException;
use yii\caching\TagDependency;
use yii\db\Query;
use yii\rbac\Assignment;
use yii\rbac\DbManager;
use yii\rbac\CheckAccessInterface;
use yii\rbac\Item;
use yii\rbac\Role as BaseRole;
use app\helpers\CacheTagHelper;
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
        if (parent::checkAccess($userId, Role::ROOT)) {
            // это root, ему можно все.
            return true;
        }

        $result = parent::checkAccess($userId, $permissionName, $params);
        Yii::info('checkAccess ' . $userId . ' to ' . $permissionName, 'access = ' . $result);

        return $result;
    }


    /**
     * Создать роль.
     *
     * @param string              $name
     * @param Project|string|null $project
     * @return Role
     */
    public function createRole($name, $project = null)
    {
        $role = Role::create($name, $project);

        return $role;
    }


    /**
     * Создает полномочие
     *
     * @param string              $name
     * @param Project|string|null $project
     * @return  Permission
     */
    public function createPermission($name, $project = null)
    {
        $permission = Permission::create($name, $project);

        return $permission;
    }


    /**
     * Создать и добавить роль.
     *
     * @param         $roleName
     * @param Project $project - если указан, создается роль ассоциированная с проектом
     * @param Item[]  $parents - элементы, чьи полномочия наследуются
     * @return \yii\rbac\Role
     */
    public function addRole($roleName, $project = null, $parents = [])
    {
        if (!isset(Role::itemLabels()[$roleName])) {
            throw new InvalidArgumentException('addRole only for built-in roles. Another use add(new Role()).');
        }
        $role = Role::create($roleName, $project);

        if (!$this->add($role)) {
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
     * @param Project $project - если указан, создается полномочие ассоциированная с проектом
     * @param Item[]  $parents - элементы, чьи полномочия наследуются
     * @return \yii\rbac\Permission
     */
    public function addPermission($permissionName, $project = null, $parents = [])
    {
        if (!isset(Permission::itemLabels()[$permissionName])) {
            throw new InvalidArgumentException('addPermission only for built-in permissions.');
        }

        $permission = Permission::create($permissionName, $project);

        if (!$this->add($permission)) {
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
            $assignment->roleName = $role;
        } else {
            if ($role instanceof BaseRole) {
                $assignment->roleName = $role->name;
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
     * @return Permission|Role the populated auth item instance (either Role or Permission)
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
     *
     */
    public function invalidateCache()
    {
        CacheTagHelper::invalidateTags(CacheTagHelper::auth());
        parent::invalidateCache();
    }


    /**
     * Получить роли проекта.
     *
     * @param Project|string $project  - проект или его суффикс
     * @param integer        $itemType - тип доступа роль/полномочие
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
            ->andWhere(['like', 'name', $suffix . '_']);
        if ($itemType) {
            $query->andwhere(['=', 'type', Role::TYPE_ROLE]);
        }

        $roles = [];
        foreach ($query->all() as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }

        return $roles;
    }


    /**
     * Получить роли пользователя
     * @param int|string $userId
     * @param int        $itemType - тип контроля доступа. 0 - все типы
     * @param bool       $distinct - не упоминать полномочия, входящие в роль
     * @return array|BaseRole[Permission|Role]
     */
    public function getRolesByUser($userId, $itemType = Item::TYPE_ROLE, $distinct = false)
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
            ->andWhere(['a.user_id' => (string)$userId]);
        if ($itemType) {
            $query->andWhere(['b.type' => $itemType]);
        }

        $roles = $this->getDefaultRoleInstances();
        foreach ($query->all($this->db) as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }
        if($distinct) {
            foreach ($roles as $role) {
                // если роль/полномочие является подролью упомянутой ранее
                if(isset($this->parents[$role->name]) ) {
                    foreach ($this->parents[$role->name] as $parent) {
                        if(isset($roles[$parent])) {
                            unset($roles[$role->name]);
                        }
                    }
                }
            }
        }

        return $roles;
    }


    /**
     * аналог ManagerInterface::getUsersByRole(), но возвращающий сущности, а не id'ы
     *
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
            ->where(['like', 'name', $project->suffix . '_'])
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
     *
     * @param Project $project
     * @return array[Permission|string] - Массив с добавленными полномочиями (или строками с ошибкой)
     */
    public function refreshProjectAccesses($project)
    {
        $existItems = $this->getRolesByProject($project, null);

        // нам нужен массив абстрактных полномочий, без проекта
        $existItems = array_map(
            function ($item) {
                list($name,) = explode('_', $item->name);

                return $name;
            },
            $existItems
        );
        $existItems = array_flip($existItems);

        $result = [];
        foreach (Permission::getProjectPermissions() as $item) {
            if (isset($existItems[$item])) {
                // полномочие уже есть в проекте
                continue;
            }

            $res = $this->addPermission($item, $project);
            if ($res) {
                $result[$item] = $res;
            } else {
                $result[$item] = 'Error in adding ' . $item;
            }
        }

        return $result;
    }


    /**
     * Получить проекты, к которым у юзера есть полномочие
     *
     * @param integer|EntityUser $userId
     * @param string             $permission
     * @return array - массив суффиксов проектов
     */
    public function getProjectsByUser($userId, $permission)
    {
        $cacheKey = $this->cacheKey . '_ProjectsByUser:' . $userId . ':' . $permission;

        $projects = $this->cache->get($cacheKey);
        if (is_array($projects)) {
            return $projects;
        }

        if ($this->checkAccess($userId, Role::ROOT)) {
            // руту доступны все проекты
            $projects = Project::find()->select(['suffix'])->asArray()->all();
            $this->cache->set($cacheKey, $projects, 0, new TagDependency(['tags' => CacheTagHelper::auth()]));

            return $projects;
        }

        // дерево полномочий
        $childrenList = $this->getChildrenList();

        if ($userId) {
            // юзер
            if ($userId instanceof EntityUser) {
                $userId = $userId->id;
            }

            // роли пользователя
            $userRoles = $this->getRolesByUser($userId, 0);
            foreach ($userRoles as $userRole) {
                if ($userRole->isProject()) {
                    // эта роль уже ассоциирована с проектом
                    $projects[$userRole->getProject()] = true;

                } else {
                    // роль глобальна, но может к ней привязаны локальные полномочия?
                    $result = [];
                    $this->getChildrenRecursive($userRole->name, $childrenList, $result);
                    foreach ($result as $item => $v) {
                        // наберем проекты с интересующим нас полномочием
                        if (Permission::getItemName($item) != $permission) {
                            continue;
                        }
                        $projects[Permission::getProjectByName($item)] = true;
                    }
                }
            }
        }

        // Соберем полномочия гостя
        $result = [];
        // все полномочия гостя
        $this->getChildrenRecursive(Role::GUEST, $childrenList, $result);
        if (empty($result)) {
            return [];
        }

        // наберем проекты с интересующим нас полномочием
        foreach ($result as $item => $v) {
            if (Permission::getItemName($item) != $permission) {
                continue;
            }
            $projects[Permission::getProjectByName($item)] = true;
        }

        $projects = array_keys($projects);

        $this->cache->set($cacheKey, $projects, 0, new TagDependency(['tags' => CacheTagHelper::auth()]));

        return $projects;
    }

}
