<?php
/**
 * User: demius
 * Date: 20.03.18
 * Time: 23:33
 */

namespace app\helpers;

use Yii;
use yii\caching\TagDependency;


/**
 * Class CacheTagHelper - Список всех использующихся тегов. Дабы не терять оные.
 *
 * @package app\helpers
 */
class CacheTagHelper
{

    /**
     * @param string|array $tags
     */
    public static function invalidateTags($tags)
    {
        TagDependency::invalidate(Yii::$app->cache, $tags);
    }


    /**
     * @param string $suffix - project suffix
     * @return string
     */
    public static function taskStat($suffix)
    {
        Yii::error($suffix);
        return 'Project-' . $suffix . '-taskStats';
    }

}
