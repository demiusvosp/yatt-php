<?php
/**
 * User: demius
 * Date: 25.03.18
 * Time: 19:13
 */

namespace app\components\auth;

use Yii;
use yii\base\Component;


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
        foreach ($this->templates as $key => $value) {
            if(is_string($value)) {
                // оформим встроенный как остальные
                $this->templates[$value] = [ 'class' => 'app\\components\\auth\\templates\\' . $value ];
                unset($this->templates[$key]);
            }
        }

        // Получим менеджер авторизации
        if(is_string($this->authManager)) {
            $this->authManager = Yii::$app->authManager;
        }

        parent::init();
    }


    /**
     * Получить список шаблонов доступа
     * @return array
     */
    public function getTemplatesList()
    {
        $list = [];
        foreach ($this->templates as $name => $template) {
            $list[$name] = $template['class']::name();
        }

        return $list;
    }

    public function buildProjectAccesses($project, $templateName)
    {
        //$rolesHierarchy = $this->templates[$templateName]::getRolesHierarchy();
    }

}