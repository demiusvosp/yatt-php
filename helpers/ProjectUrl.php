<?php
/**
 * Created by PhpStorm.
 * User: demius
 * Date: 11.10.17
 * Time: 23:32
 */

namespace app\helpers;

use yii\helpers\Url;

use app\models\entities\Project;


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
        }

        return parent::to($url, $scheme);
    }
}