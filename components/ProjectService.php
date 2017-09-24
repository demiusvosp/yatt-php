<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 24.09.17
 * Time: 3:07
 */

namespace app\components;

use yii\base\Component;
use Yii;

use app\models\entities\Project;
use app\models\queries\ProjectQuery;

class ProjectService extends Component
{
    /** @var Project */
    public $project = null;

// Наметки получения проекта сервисом. В даный момент необходимости сервиса нет,
// как нет и четкого понимнаия получения сервисом проекта в каждом запросе, который им воспользуется.
//    function __construct(array $config)
//    {
//        // получаем себе текущий проект
//        if(isset($config['project_id'])) {
//            $this->project = Project::findOne($config['project_id']);
//        }
//        if(Yii::$app->session && Yii::$app->session->has('project_id')) {// а кто будет класть его в сессию?
//            $this->project = Project::findOne(Yii::$app->session['project_id']);
//        }
//        if($this->countAllowProjects() == 1) {
//            $this->project = static::allowProjectsQuery()->one();
//        }
//
//
//        parent::__construct($config);
//    }


}