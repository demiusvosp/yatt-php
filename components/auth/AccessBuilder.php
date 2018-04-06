<?php
/**
 * User: demius
 * Date: 25.03.18
 * Time: 19:13
 */

namespace app\components\auth;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;


/**
 * Class AccessBuilder - создание набора доступов
 *
 * @package app\components\auth
 */
class AccessBuilder extends Component
{
    /**
     * Массив шаблонов наборов доступа
     * @var array
     */
    public $templates = [];

    /**
     * @var AuthProjectManager
     */
    public $authManager = 'authManager';


    public function init()
    {
        // нормализуем список шаблонов
        $this->templates = array_flip($this->templates);

        // Получим менеджер авторизации
        if(is_string($this->authManager)) {
            $this->authManager = Yii::$app->authManager;
        }

        parent::init();
    }


    /**
     * Загрузить указанный шаблон
     *
     * @param string $templateName
     * @return array|false
     * @throws InvalidConfigException
     */
    protected function loadTemplate($templateName)
    {
        if(!is_array($this->templates[$templateName])) {
            // пробуем загрузить шаблон по стандартному пути
            $this->templates[$templateName] = require(__DIR__.'/templates/' . $templateName.'.php');
            if($this->templates[$templateName] === false) {
                // нет, поищем по указанному
                $this->templates[$templateName] = require(Yii::$app->basePath . $templateName.'php');
                if($this->templates[$templateName] === false) {
                    throw new InvalidConfigException('Access template '.$templateName.'not found');
                }
                // надо ли нормализовывать такой идентификатор шаблона?
            }
        }

        return $this->templates[$templateName];
    }

    /**
     * Получить список шаблонов доступа
     * @return array
     */
    public function getTemplatesList()
    {
        $list = [];
        foreach ($this->templates as $name => $template) {
            $template = $this->loadTemplate($name);
            $list[$name] = $template['name'];
        }

        return $list;
    }


    /**
     * @param $project
     * @param string $templateName
     */
    public function buildProjectAccesses($project, $templateName)
    {
        $template = $this->loadTemplate($templateName);

        foreach ($template['hierarchy'] as $itemName => $parents) {
            $this->buildRole($project, $templateName, $itemName, $parents);
        }
    }


    /**
     * @param $project
     * @param $templateName
     * @param $item
     * @param $parents - полномочия, наследуемые ролью
     * @return \yii\rbac\Role
     */
    protected function buildRole($project, $templateName, $item, $parents)
    {
        // роли уже построеные в предыдущие этапы.
        static $buildedItems = [];

        // роли/полномочия, которые наследует эта роль
        $doneParents = [];
        $template = $this->templates[$templateName];
        if($item == '?') {
            $item = Role::GUEST;
        }

        if(isset($buildedItems[$item])) {
            // Эту роль уже обрабатывали (например при создании других ролей)
            return $buildedItems[$item];
        }

        foreach ($parents as $parent) {
            if(isset(Permission::itemLabels()[$parent])) {
                // это полномочие, его и включаем
                $doneParents[$parent] = $this->buildPermission($project, $parent);
            } elseif(isset($template['hierarchy'][$parent])) {
                // это другая роль проекта, которая включается в текущую
                if(!isset($doneParents[$parent])) {
                    // и она еще не обработанна
                    $doneParents[$parent] = $this->buildRole($project, $templateName, $parent, $template['hierarchy'][$parent]);
                }
            } else {
                throw new \DomainException('Cannot build item '.$item.', error in '.$parent);
            }
        }

        if(Accesses::isGlobal($item)) {
            // это глобальная роль, просто на неё нужно навешать полномочий проекта
            $role = $this->authManager->getRole($item);
        } else {
            // это собственая роль проекта
            $role = Role::create(
                $item,
                $project,
                isset($template['roles'][$item]) ? $template['roles'][$item] : $item
            );
            $this->authManager->add($role);
        }

        foreach ($doneParents as $child) {
            $this->authManager->addChild($role, $child);
        }

        $buildedItems[$role->getId()] = $role;
        return $role;
    }


    /**
     * @param $project
     * @param $item
     * @return \yii\rbac\Permission
     */
    protected function buildPermission($project, $item)
    {
        $permission = $this->authManager->getPermission(Permission::getFullName($item, $project));

        if(!$permission) {
            $permission = $this->authManager->addPermission($item, $project);
        }

        return $permission;
    }

}