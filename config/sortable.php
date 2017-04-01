<?php

return [
    'entities' => [
        'pages' => Soda\Cms\Database\Models\Page::class,

        'page-types.fields' => [
            'entity'   => Soda\Cms\Database\Models\PageType::class,
            'relation' => 'fields',
        ],

        'block-types.fields' => [
            'entity'   => Soda\Cms\Database\Models\BlockType::class,
            'relation' => 'fields',
        ],
    ],
];
