<?php
/**
 * User: demius
 * Date: 23.03.18
 * Time: 22:52
 */

namespace app\components\auth\templates;


use Yii;
use app\components\auth\Permission;


return [
    'name'      => Yii::t('access/templates', 'Admin-Employee-View'),
    'roles'     => [
        'Admin'    => Yii::t('access/templates', 'Project admin'),
        'Employee' => Yii::t('access/templates', 'Project employee'),
        'View'     => Yii::t('access/templates', 'Project watcher'),
    ],
    'hierarchy' => [
        'Admin'    => [
            Permission::PROJECT_SETTINGS,
            'Employee',
            Permission::MANAGE_COMMENT,
        ],
        'Employee' => [
            'View',
            Permission::OPEN_TASK,
            Permission::EDIT_TASK,
            Permission::CHANGE_STAGE,
            Permission::CLOSE_TASK,
        ],
        'View'     => [
            Permission::PROJECT_VIEW,
            Permission::CREATE_COMMENT,
        ],
    ],
];
