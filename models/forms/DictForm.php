<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 28.09.17
 * Time: 14:43
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;

use app\models\entities\Project;
use app\models\entities\IRefProject;

/**
 * Class DictForm - Форма справочник. Содерит подборку значений справочника.
 * В конфиге необходимо указать коллекцию, и модель значения справочника
 * @package app\models\forms
 */
class DictForm extends Model
{
    /** @var array коллекция справочника */
    public $items;

    /** @var  string класс модели значения справочника */
    public $itemClass;

    /** @var  Project проект, которому принадлежит справочник */
    public $project = null;


    public function __construct(array $config = ['items' => null])
    {
        if(!$config['items']) {
            throw new \DomainException('need items field');
        }
        if(!$config['itemClass']) {
            throw new \DomainException('need itemClass field');
        }

        parent::__construct($config);
    }


    public function init()
    {
        parent::init();
        // и новая для создания нового занчения
        $newItem = Yii::createObject($this->itemClass, []);
        if($this->project && $newItem instanceof IRefProject) {
            $newItem->project = $this->project;
        }
        $this->items[] = $newItem;
    }


    public function load($data, $formName = null)
    {
        return Model::loadMultiple($this->items, $data, $formName);
    }


    public function validate($attributeNames = null, $clearErrors = true)
    {
        $newItem = end($this->items);
        if( !$newItem->validate($attributeNames, $clearErrors)) {
            // последний элемент это новая запись в словарь. Если она не прошла валидацию, значит не надо её добавлять
            array_pop($this->items);
        }
        return Model::validateMultiple($this->items, $attributeNames);
    }


    public function save()
    {
        foreach ($this->items as $item) {
            $item->save(false);
        }
    }

}
