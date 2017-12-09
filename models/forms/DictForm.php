<?php
/**
 * User: demius
 * Date: 09.12.17
 * Time: 14:00
 */

namespace app\models\forms;


use yii\base\InvalidParamException;
use yii\base\Model;
use yii\db\ActiveQuery;


class DictForm extends Model
{
    /** @var  string - Dict className */
    public $class;

    /** @var  integer - Dict id */
    public $id;

    /** @var  string - Project suffix */
    public $suffix;

    public function rules()
    {
        return [
            [['class', 'id'], 'required'],

            [['class', 'suffix'], 'string'],
            [['id'], 'integer'],
        ];
    }


    /**
     * Получить элемент справочника
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDict()
    {
        if(!class_exists($this->class)) {
            throw new InvalidParamException('unknown dict class');
        }

        $dict = (new ActiveQuery($this->class))->andWhere(['id' => $this->id])->one();
        if(!$dict) {
            throw new InvalidParamException('cannot find dict');
        }

        return $dict;
    }
}