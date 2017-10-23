<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 22.10.17
 * Time: 1:37
 */

namespace app\models\forms;

use yii\base\Model;
use Yii;

class CloseTaskForm extends Model
{

    public $index;
    public $suffix;

    public $close_reason;

    public function rules()
    {
        return [
            [['close_reason', 'index', 'suffix'], 'required'],
            [['close_reason', 'index'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'close_reason' => Yii::t('task', 'Close reason')
        ];
    }
}