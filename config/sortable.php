<?php

return [
    'entities' => [
        'pages' => Soda\Cms\Database\Models\Content::class,

        'page-types.fields' => [
            'entity'   => Soda\Cms\Database\Models\ContentType::class,
            'relation' => 'fields',
        ],

        'block-types.fields' => [
            'entity'   => Soda\Cms\Database\Models\ContentType::class,
            'relation' => 'fields',
        ],
    ],
];
