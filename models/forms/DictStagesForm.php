<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 19.10.17
 * Time: 20:56
 */

namespace app\models\forms;

use yii\base\Model;
use app\models\entities\DictStage;

class DictStagesForm extends DictForm
{
    public function init()
    {
        parent::init();
        /*
         *  новый итем создается последним, но в данной форме последним идет "Закрыто", и новые этапы создаются до него,
         *  поэтому меняем местами два последних элемента
         */
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

}
