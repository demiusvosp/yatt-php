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
use app\models\entities\DictBase;
use app\models\queries\IDictRelatedEntityQuery;
use yii\db\ActiveRecord;


/**
 * Class DictEditForm - Форма справочник. Содерит подборку значений справочника.
 * В конфиге необходимо указать коллекцию, и модель значения справочника
 * @package app\models\forms
 */
class DictEditForm extends Model
{
    /** @var array[DictBase] коллекция справочника */
    public $items;

    /** @var  string класс модели значения справочника */
    public $itemClass;

    /** @var  Project проект, которому принадлежит справочник */
    public $project = null;

    /** @var  array [ [<Entity-класс>, <поле>], [], ...] которые используют справочник */
    public $relatedFields = [];

    /** @var array количество ассоциированных со справочником объектов */
    public $relatedItemCount = [];

    public function __construct(array $config = ['items' => null])
    {
        if(!$config['items']) {
            $this->items = [];
        }
        if(!$config['itemClass']) {
            throw new \DomainException('need itemClass field');
        }

        parent::__construct($config);
    }


    public function init()
    {
        parent::init();

        // посчитаем статистику
        foreach ($this->relatedFields as $relatedItem) {
            /** @var ActiveRecord $relatedItem[0] */
            $query = $relatedItem[0]::find();
            if($query instanceof IDictRelatedEntityQuery) {
                $stat = $query->relatedEntityCount($this->project, $relatedItem[1]);
                if($stat) {
                    foreach ($stat as $dict => $item) {
                        if(isset($this->relatedItemCount[$dict])) {
                            $this->relatedItemCount[$dict] += $item;
                        } else {
                            $this->relatedItemCount[$dict] = $item;
                        }
                    }
                }
            }
        }

        // новый элемент справочника
        $newItem = Yii::createObject($this->itemClass, []);
        if($this->project && $newItem instanceof DictBase) {
            $newItem->project_id = $this->project->id;
        }
        $newItem->position = count($this->items);
        $this->items[] = $newItem;
    }


    public function tableName()
    {
        return end($this->items)->tableName();
    }


    public function load($data, $formName = null)
    {
        return Model::loadMultiple($this->items, $data, $formName);
    }


    public function validate($attributeNames = null, $clearErrors = true)
    {
        foreach ($this->items as $index => $item) {
            if($item->isNewRecord) {
                if(empty($item->name)) {
                    // Если новая запись не имеет имени, значит её не заполняли - удаляем
                    unset($this->items[$index]);
                }
            }
        }
        return Model::validateMultiple($this->items, $attributeNames);
    }


    public function save()
    {
        foreach ($this->items as $item) {
            if($this->project) {
                $item->project_id = $this->project->id;
            }
            if(empty($item->description)) {
                $item->description = $item->name;
            }

            $item->save(false);
        }
    }

}
