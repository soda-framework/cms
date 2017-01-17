<?php

return [
    'entities' => [
        'pages' => 'soda.page.model',

        'page-types.fields' => [
            'entity'   => 'soda.page-type.model',
            'relation' => 'fields',
        ],

        'blocks' => 'soda.block.model',

        'block-types.fields' => [
            'entity'   => 'soda.block-type.model',
            'relation' => 'fields',
        ],
    ],
];
