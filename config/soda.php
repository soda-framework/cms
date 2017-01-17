<?php

return [
    'hint_path' => 'soda',
    'cms_path'  => 'cms',
    'upload'    => [
        'folder' => null,
        'driver' => 'soda.public',
    ],
    'menu'      => [
        'applications'         => false,
        'application-settings' => false,
        'pages'                => true,
        'page-types'           => true,
        'blocks'               => true,
        'block-types'          => true,
        'fields'               => true,
        'users'                => true,
        'navigation'           => false,
    ],
    'fields' => [
        'checkbox'     => Soda\Cms\Components\Forms\Fields\Checkbox::class,
        'code'         => Soda\Cms\Components\Forms\Fields\Code::class,
        'datetime'     => Soda\Cms\Components\Forms\Fields\Datetime::class,
        'dropdown'     => Soda\Cms\Components\Forms\Fields\Dropdown::class,
        'fancy_upload' => Soda\Cms\Components\Forms\Fields\FancyUpload::class,
        'json'         => Soda\Cms\Components\Forms\Fields\Json::class,
        'lat_lon'      => Soda\Cms\Components\Forms\Fields\LatLon::class,
        'password'     => Soda\Cms\Components\Forms\Fields\Password::class,
        'static_text'  => Soda\Cms\Components\Forms\Fields\StaticText::class,
        'text'         => Soda\Cms\Components\Forms\Fields\Text::class,
        'textarea'     => Soda\Cms\Components\Forms\Fields\Textarea::class,
        'tinymce'      => Soda\Cms\Components\Forms\Fields\Tinymce::class,
        'upload'       => Soda\Cms\Components\Forms\Fields\Upload::class,
        'url'          => Soda\Cms\Components\Forms\Fields\Url::class,
    ],
];
