<?php

/*
|--------------------------------------------------------------------------
| Terminology
|--------------------------------------------------------------------------
|
| The following language lines are for terms used to describe the main
| building blocks that create the interface
|
*/

return [
    'home'                => '집', // Home/front page
    'dashboard'           => '계기반', // Dashboard screen for CMS, displaying miscellaneous widgets, metrics, graphs, etc
    'content'             => '함유량', // Encapsulates all content, webpages, images, etc used to create the website
    'content_plural'      => '내용',
    'content_type'        => '컨텐츠 타입', // Content items are given a 'type', to distinguish distinctive feature. For example, a 'Product' page versus a 'Profile' page.
    'content_type_plural' => '콘텐츠 유형',
    'block'               => '블록', // Blocks are types of content that are used collectively to build a page. For example, a page may consist of multiple FAQ sections. Each section is referred to as a 'block'
    'block_plural'        => '블록들',
    'block_type'          => '블록 유형', // Similar to content types, each block is given a type to distinguish it's unique features.
    'block_type_plural'   => '블록 유형',
    'field'               => '들', // Fields are used to create content/block types. For example, a text 'field' could be added to a content type to provide a heading.
    'field_plural'        => '전지',
    'scaffolding'         => '발판', // Encapsulates content/block types and fields. 'Scaffolding' menu items are provided to users who have the ability to build the structure of the website
    'settings'            => '설정', // Title for section to configure settings for the CMS
    'user'                => '사용자', // Encapsulates all user options and settings
    'user_plural'         => '사용자',
    'role'                => '역할', // Users can be assigned 'roles' or groups, which provide a set of permissions to the user
    'role_plural'         => '역할',
    'permission'          => '허가', // Permissions are delegated to roles to allow access to specified areas and features of the CMS
    'permission_plural'   => '허가',
    'quicklink'           => '빠른 링크', // Refers to pages/areas of the CMS which are bookmarked/favourited for the user
    'quicklink_plural'    => '빠른 링크',
];
