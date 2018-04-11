<?php

use app\models\entities\Project;

return [
    'priv' => [
        'suffix' => 'PRIV',
        'name' => 'Приватный проект',
        'description' => 'Fugiat ut officia eos et nam impedit. In officiis quos esse. Omnis vero magnam et eum modi voluptatem qui repudiandae. Cum cupiditate voluptatem id perspiciatis officiis fuga.',
        //'public' => Project::STATUS_PUBLIC_AUTHED,
        'config' => [],
    ],
    'oth' => [
        'suffix' => 'OTH',
        'name' => 'Еще один проект',
        'description' => 'Fugiat ut officia eos et nam impedit. In officiis quos esse. Omnis vero magnam et eum modi voluptatem qui repudiandae. Cum cupiditate voluptatem id perspiciatis officiis fuga.',
        //'public' => Project::STATUS_PUBLIC_ALL,
        'config' => [],
    ],
];
