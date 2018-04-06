<?php

namespace app\models\queries;

use Yii;
use yii\db\ActiveQuery;
use yii\caching\TagDependency;
use app\components\auth\AuthProjectManager;
use app\components\auth\Permission;
use app\helpers\CacheTagHelper;
use app\models\entities\Project;


/**
 * This is the ActiveQuery class for [[\app\models\entities\Project]].
 *
 * @see \app\models\entities\Project
 */
class ProjectQuery extends ActiveQuery
{

    public function __construct($modelClass, $withArchived = false, array $config = [])
    {
        if(!$withArchived) {
            $this->andActive();
        }
        parent::__construct($modelClass, $config);
    }


    /**
     * Только проекты не в архиве
     * @param bool $active
     * @return $this
     */
    public function andActive($active = true)
    {
        return $this->andWhere(['archived' => !$active]);
    }


    /**
     * Жадно загрузить вместе со справочниками
     *
     * @param bool $dicts
     * @return $this
     */
    public function withDicts($dicts = false)
    {
        if (!$dicts) {
            $dicts = ['dict_state'];
        }

        return $this->with($dicts);
    }


    /**
     * Query проектов доступных текущему пользователю
     *
     * @return ProjectQuery
     */
    static function allowProjectsQuery()
    {
        $query = Project::find();

        /** @var AuthProjectManager $auth */
        $auth = Yii::$app->get('authManager');

        $suffixes = $auth->getProjectsByUser(Yii::$app->user->id, Permission::PROJECT_VIEW);
        $query->andWhere(['in', 'suffix', $suffixes]);
        $query->cache(0, new TagDependency(['tags' => CacheTagHelper::auth()]));

        return $query;
    }


    /**
     * Количество проектов доступных текущему пользователю
     *
     * @TODO второй раз запускать тяжелый запрос?
     *
     * @return int
     */
    static function countAllowProjects()
    {
        $query = static::allowProjectsQuery();

        return $query->count();
    }


    /**
     * Список проектов доступных пользователю
     *
     * @param bool   $forSelect false - [ ['id'=><id>, 'name'=><name>], ... ]; true - [ <id> => <name>, ... ]
     * @param string $indexBy
     * @return array [<id> => <name>]
     */
    static function allowProjectsList($forSelect = false, $indexBy = 'id')
    {
        $query = static::allowProjectsQuery();
        if ($forSelect) {
            $query->select([$indexBy, 'name'])->indexBy($indexBy)->asArray();
        }

        $projects = $query->all();

        return $projects;
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\Project[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\models\entities\Project|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function byId($id)
    {
        if (is_string($id)) {
            return $this->where(['suffix' => $id])->one();
        }
        if (is_int($id)) {
            return $this->where(['id' => $id])->one();
        }
        throw new \InvalidArgumentException('unknow ' . gettype($id));
    }

}
