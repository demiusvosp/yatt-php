<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 19.10.17
 * Time: 20:56
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;

use app\models\entities\DictStage;

class DictStagesForm extends DictForm
{
    public function init()
    {
        if(count($this->items) == 0) {
            // справочники еще не заданы, создаем вшитые
            $this->items[] = $this->createOpenItem();
            $this->items[] = $this->createCloseItem();
        }
        parent::init();
        // перемещаем статус закрыта под новый статус
        $count = count($this->items);
        $new = $this->items[$count-1];
        $this->items[$count-1] = $this->items[$count-2];
        $this->items[$count-2] = $new;
    }


    public function validate($attributeNames = null, $clearErrors = true)
    {
        foreach ($this->items as $index => $item) {
            if($item->isNewRecord && $item->type == DictStage::TYPE_USER) {
                if(empty($item->name)) {
                    // Если новая запись не имеет имени, значит её не заполняли - удаляем
                    unset($this->items[$index]);
                }
            }
        }
        return Model::validateMultiple($this->items, $attributeNames);
    }


    public function createOpenItem()
    {
        return new DictStage(
            [
                'name' => Yii::t('dicts', 'Open'),
                'description' => Yii::t('dicts', 'Open task'),
                'type' => DictStage::TYPE_OPEN
            ]
        );
    }


    public function createCloseItem()
    {
        return new DictStage(
            [
                'name' => Yii::t('dicts', 'Close'),
                'description' => Yii::t('dicts', 'Close task'),
                'type' => DictStage::TYPE_CLOSED
            ]
        );
    }
}