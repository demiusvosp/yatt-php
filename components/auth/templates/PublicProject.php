<?php
/**
 * User: demius
 * Date: 28.03.18
 * Time: 21:58
 */

namespace app\components\auth\templates;


use Yii;
use app\components\auth\Role;
use app\components\auth\Permission;


return [
    'name'      => Yii::t('access/templates', 'Public project'),
    'roles'     => [
        'Admin'    => Yii::t('access/templates', 'Project admin'),
        'Employee' => Yii::t('access/templates', 'Project employee'),
    ],
    'hierarchy' => [
        'Admin'         => [
            Permission::PROJECT_SETTINGS,
            'Employee',
            Permission::MANAGE_COMMENT,
        ],
        'Employee'      => [
            Permission::OPEN_TASK,
            Permission::EDIT_TASK,
            Permission::CHANGE_STAGE,
            Permission::CLOSE_TASK,
        ],
        Role::GUEST => [
            Permission::PROJECT_VIEW,
            Permission::CREATE_COMMENT,
        ],
    ],
];
