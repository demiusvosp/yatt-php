<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 19.10.17
 * Time: 23:03
 */

namespace app\models\entities;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\ProjectHelper;
use app\models\queries\DictBaseQuery;


/**
 * Class DictBase - Абстрактный родитель для всех моделей основных справочников
 *
 * @property integer $id
 * @property integer $project_id
 * @property string  $name
 * @property string  $description
 * @property integer $position
 *
 * @property Project $project
 * @property Task[]  $tasks
 */
abstract class DictBase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'position'], 'integer'],
            [['name'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [
                ['project_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Project::className(),
                'targetAttribute' => ['project_id' => 'id'],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('dicts', 'ID'),
            'project_id'  => Yii::t('dicts', 'Project ID'),
            'name'        => Yii::t('dicts', 'Name'),
            'description' => Yii::t('dicts', 'Description'),
            'position'    => Yii::t('dicts', 'Position'),
        ];
    }


    /**
     * @return DictBaseQuery
     * @throw \DomainException
     */
    public static function find()
    {
        /*
         * Да, у нас есть DictBaseQuery, но это просто общие для всех справочников функции поиска,
         * инстанцировать его нельзя, так как он не знает к какому справочнику себя применять
         */
        throw new \DomainException('Dict должен отдавать свой Query-класс');
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }


    /**
     * Получить проект.
     * Стоит подумать, а не вынести ли это в трейт
     * @return \yii\db\ActiveQuery|Project|null
     */
    public function getProject()
    {
        if(ProjectHelper::currentProject() && ProjectHelper::currentProject()->id == $this->project_id) {
            // мы в текущем проекте, незачем его искать в БД
            return ProjectHelper::currentProject();
        }

        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }


    /**
     * Количество задач использующих значение справочника
     * @return integer
     */
    abstract public function countTask();


    /**
     * Добавить справочник к проекту
     * @param Project $project
     * @param bool    $save
     * @return bool
     */
    public function append(Project $project, $save = true)
    {
        $this->project_id = $project->id;
        $this->position = static::find()->getLastPosition($project) + 1;
        if($save) {
            return $this->save();
        }
        return true;
    }


    /**
     * запрет смены позиции элемента справочника
     *
     * @return bool
     */
    public function disableReposition()
    {
        return false;
    }


    /**
     * запрет удаления элемента справочника
     *
     * @return bool
     */
    public function disableDelete()
    {
        return false;
    }
}