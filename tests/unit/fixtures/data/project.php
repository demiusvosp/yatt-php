<?php

return [
    'pub' => [
        'suffix' => 'PUB',
        'name' => 'Публичный',
        'description' => 'Видный всем проект, где больше всего всяких фич. Для разработки и отладки.',
        'editorType' => 'md',
        'access_template' => 'PublicProject',
    ],
    'std' => [
        'suffix' => 'STD',
        'name' => 'Стандартный проект',
        'description' => 'Проект со стандартной сеткой ролей.',
        'editorType' => 'md',
        'access_template' => 'EmployeeView',
    ],
    'petr' => [
        'suffix' => 'PETR',
        'name' => 'Личный проект Петра',
        'description' => 'Личный проект Петра.',
        'editorType' => 'md',
        'access_template' => 'EmployeeView',
    ],
    'oth' => [
        'suffix' => 'OTH',
        'name' => 'Еще один проект',
        'description' => 'Fugiat ut officia eos et nam impedit. In officiis quos esse. Omnis vero magnam et eum modi voluptatem qui repudiandae. Cum cupiditate voluptatem id perspiciatis officiis fuga.',
        'editorType' => 'wysiwyg',
        'access_template' => 'EmployeeView',
    ],
];
