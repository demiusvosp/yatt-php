<?php
/**
 * User: demius
 * Date: 02.11.17
 * Time: 23:31
 */

namespace app\widgets;


use app\helpers\HtmlBlock;
use kartik\select2\Select2;
use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\web\JsExpression;
use app\models\entities\User;
use app\models\queries\UserQuery;


class UserSelect extends Select2
{
    /** @var string - property со связью с таблицей user, откуда можно взять имя пользователя */
    public $userField;

    /** @var  array - дополнительное условие на пользователей */
    public $where;

    public function init()
    {
        $view = $this->getView();

        $JsCallbacks = <<<JS
    var formatRepoSelection = function (repo) {
        return repo.username || repo.text;
    };
JS;
        $view->registerJs($JsCallbacks, View::POS_HEAD);
        $view->registerJs(HtmlBlock::userItemJs(), View::POS_HEAD);

        $value = Html::getAttributeValue($this->model, $this->attribute);
        if($value && $this->userField) {
            $field = $this->userField;
            /** @var User $user */
            $user = $this->model->$field;
            $this->initValueText = HtmlBlock::userItem($user);
        } else {
            $this->initValueText = Yii::t('common', 'Choose user');
        }
        $users = UserQuery::findUserByNameMail();
        foreach ($users as $user) {
            $this->data[$user['id']] = $user['username'];
        }

        $this->theme = static::THEME_DEFAULT;
        $this->options = [
            'encode' => false,
            'placeholder' => Yii::t('common', 'Choose user')
        ];
        $this->pluginOptions = [
                'allowClear' => true,
//                'minimumInputLength' => 3, пока неудобно, либо data, либо ajax. А так, чтобы выдать список популярных, но при вводе предложить по имени увы:(
//                'ajax' => [
//                    'url' => Url::to(['/user/find-for-choose']),
//                    'dataType' => 'json',
//                    'data' => new JsExpression('function(params) { return {query:params.term}; }')
//                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('userItemJs'),
                'templateSelection' => new JsExpression('formatRepoSelection'),
            ];

        parent::init();
    }
}