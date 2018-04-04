<?php
/**
 * User: demius
 * Date: 28.03.18
 * Time: 22:00
 */

namespace app\components\auth\templates;

use Yii;
use app\components\auth\Permission;


return [
    'name'      => Yii::t('access/templates', 'Personal project'),
    'roles'     => [
        'projectOwner' => Yii::t('access/templates', 'Project owner'),
    ],
    'hierarchy' => [
        'projectOwner' => [
            Permission::PROJECT_SETTINGS,
            Permission::MANAGE_COMMENT,
            Permission::OPEN_TASK,
            Permission::EDIT_TASK,
            Permission::CHANGE_STAGE,
            Permission::CLOSE_TASK,
            Permission::PROJECT_VIEW,
            Permission::CREATE_COMMENT,
        ],
    ],
];

