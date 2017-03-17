<?php

return [
    'entities' => [
        'pages' => Page::class,

        'page-types.fields' => [
            'entity'   => PageType::class,
            'relation' => 'fields',
        ],

        'blocks' => Block::class,

        'block-types.fields' => [
            'entity'   => BlockType::class,
            'relation' => 'fields',
        ],
    ],
];
