<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 23:32
 */

namespace app\helpers;

use yii\base\InvalidParamException;
use yii\helpers\Url;
use app\models\entities\Project;
use app\models\entities\Task;


class ProjectUrl extends Url
{
    public static function to($url = '', $scheme = false)
    {
        if(is_array($url)) {
            if(isset($url['project']) && $url['project'] instanceof Project) {
                $url['suffix'] = strtolower($url['project']->suffix);
                unset($url['project']);
            }
            if(isset($url['suffix'])) {
                $url['suffix'] = strtolower($url['suffix']);
            }

            if(strpos($url[0], '/') !== 0) {
                // нет лидирующего слеша в роуте, ставим слеш, чтобы из любого места/модуля сделать ссылку на проект
                $url[0] = '/' . $url[0];
            }
        }

        return parent::to($url, $scheme);
    }


    /**
     * @param Project|string $project
     * @return string
     */
    public static function toProject($project)
    {
        if(is_string($project)) {
            return static::to(['/project/overview', 'suffix' => $project]);
        }
        if($project instanceof Project) {
            return static::to(['/project/overview', 'project' => $project]);
        }
        throw new InvalidParamException('toProject need Project or project suffix');
    }


    /**
     * @param Task $task
     * @return string
     */
    public static function toTask($task)
    {
        return static::to(['/task/view', 'suffix' => $task->suffix, 'index'=>$task->index]);
    }
}