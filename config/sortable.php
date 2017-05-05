<?php

return [
    'entities' => [
        'content' => Soda\Cms\Database\Models\Content::class,

        'content-types.fields' => [
            'entity'   => Soda\Cms\Database\Models\ContentType::class,
            'relation' => 'fields',
        ],

        'block-types.fields' => [
            'entity'   => Soda\Cms\Database\Models\ContentType::class,
            'relation' => 'fields',
        ],
    ],
];
