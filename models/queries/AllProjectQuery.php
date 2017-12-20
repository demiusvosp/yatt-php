<?php
/**
 * User: demius
 * Date: 20.12.17
 * Time: 22:22
 */

namespace app\models\queries;

use yii\db\ActiveQuery;


/**
 * Class AllProjectQuery - класс для тех систем, которым нужны в том числе архивные проекты
 *
 * @package app\models\queries
 */
class AllProjectQuery extends ProjectQuery
{

    public function init()
    {
        ActiveQuery::init();
    }
}