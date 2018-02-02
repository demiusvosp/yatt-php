<?php
/**
 * User: demius
 * Date: 30.10.17
 * Time: 21:02
 */

namespace app\modules\admin\models;


use Yii;
use app\helpers\Access;
use app\models\queries\ProjectQuery;


class Project extends \app\models\entities\Project
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    // К сожалению я не нашел простого способа узнать во вьюхе, что аттрибут небезопасный.
    // Придется передавать это туда вот так
    public $disableAdmin = true;

    /** @var bool можно оставлять комментарии к проекту */
    public $enableCommentProject = false;

    /** @var bool можно оставлять комментарии к закрытым задачам */
    public $enableCommentToClosed = false;

    public $editorType;


    public function init()
    {
        $this->editorType = Yii::$app->params['defaultEditor'];
        parent::init();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['enableCommentProject'], 'boolean'],
        ]);
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'enableCommentProject' => Yii::t('project', 'Enable comment to project'),
            'enableCommentToClosed' => Yii::t('project', 'Enable comment to closed tasks'),
            'editorType' => Yii::t('project', 'Text editor type'),
        ]);
    }


    public function scenarios()
    {
        $fields = [
            'suffix', 'name', 'description', 'public',
            'enableCommentProject', 'enableCommentToClosed', 'editorType'
        ];

        if (Yii::$app->user->can(Access::ACCESS_MANAGEMENT)) {
            $fields[] = 'admin_id';
            $this->disableAdmin = false;
        }

        return [
            static::SCENARIO_DEFAULT => [],
            static::SCENARIO_EDIT    => $fields,
            static::SCENARIO_CREATE  => array_merge($fields, ['suffix']),
        ];
    }


    /**
     * Собираем все настройки проекта
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->enableCommentProject = $this->getConfigItem('enableCommentProject');
        $this->enableCommentToClosed = $this->getConfigItem('enableCommentToClosed');
        $this->editorType = $this->getConfigItem('editorType', Yii::$app->params['defaultEditor']);
    }


    /**
     * Сохраняем настройки
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->setConfigItem('enableCommentProject', $this->enableCommentProject);
        $this->setConfigItem('enableCommentToClosed', $this->enableCommentToClosed);
        $this->setConfigItem('editorType', $this->editorType);

        return parent::beforeSave($insert);
    }


    public static function find()
    {
        return new ProjectQuery(get_called_class(), true);
    }

}
