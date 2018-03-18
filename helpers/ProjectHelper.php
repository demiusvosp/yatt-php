<?php
/**
 * User: demius
 * Date: 18.03.18
 * Time: 22:56
 */

namespace app\helpers;


use Yii;
use yii\helpers\ArrayHelper;
use app\base\BaseProjectController;
use app\models\entities\Project;
use app\models\entities\DictStage;
use app\models\queries\DictVersionQuery;


/**
 * Class ProjectHelper - различные функции кассательно текущего проекта
 *
 * @package app\helpers
 */
class ProjectHelper
{
    protected static $_project = null;

    /**
     * @return Project|null
     */
    public static function currentProject()
    {
        if(self::$_project) {
            return self::$_project;
        }

        if(Yii::$app->controller instanceof BaseProjectController) {
            self::$_project =  Yii::$app->controller->project;

            return self::$_project;
        } else {

            return null;
        }
    }

    /**
     * Получить справочники этапов задачи
     * @return array
     */
    public static function getStagesList()
    {
        $list = [];
        /** @var DictStage $stage */
        foreach (self::currentProject()->stages as $stage) {
            if ($stage->isClose()) {// нельзя выбрать этап закрыта, надо закрывать кнопкой.
                continue;
            }
            $list[$stage->id] = $stage->name;
        }
        return $list;
    }


    /**
     * Получить справочники типов задачи
     * @return array
     */
    public static function getTypesList()
    {
        $list = [];
        foreach (self::currentProject()->types as $type) {
            if (!$type) break;

            $list[$type->id] = $type->name;
        }
        return ArrayHelper::merge([ null => Yii::t('common', 'Not set')], $list);
    }


    /**
     * Список версий проекта.
     * @param $open true - те в которых можно открывать задачи, false - в которых можно закрывать
     * @return array
     */
    public static function getVersionList($open)
    {
        $list = [];
        /** @var DictVersionQuery $query */
        $query = self::currentProject()->getVersions();
        if ($open) {
            $query->andForOpen();
        } else {
            $query->andForClose();
        }
        $versions = $query->all();

        foreach ($versions as $version) {
            if (!$version) break;

            $list[$version->id] = $version->name;
        }
        return ArrayHelper::merge([ null => Yii::t('common', 'Not set')], $list);
    }


    /**
     * Получить справочники трудоемкости задачи
     * @return array
     */
    public static function getDifficultyList()
    {
        $list = [];
        foreach (self::currentProject()->difficulties as $level) {
            if (!$level) break;

            $list[$level->id] = $level->name;
        }
        return ArrayHelper::merge([ null => Yii::t('common', 'Not set')], $list);
    }


    /**
     * Получить справочники категорий задачи
     * @return array
     */
    public static function getCategoryList()
    {
        $list = [];
        foreach (self::currentProject()->categories as $category) {
            if (!$category) break;

            $list[$category->id] = $category->name;
        }
        return ArrayHelper::merge([ null => Yii::t('common', 'Not set')], $list);
    }


}