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
    'home'                => 'home',          // Home/front page
    'dashboard'           => 'dashboard',     // Dashboard screen for CMS, displaying miscellaneous widgets, metrics, graphs, etc
    'content'             => 'content',       // Encapsulates all content, webpages, images, etc used to create the website
    'content_plural'      => 'content',
    'content_type'        => 'content type',  // Content items are given a 'type', to distinguish distinctive feature. For example, a 'Product' page versus a 'Profile' page.
    'content_type_plural' => 'content types',
    'block'               => 'block',         // Blocks are types of content that are used collectively to build a page. For example, a page may consist of multiple FAQ sections. Each section is referred to as a 'block'
    'block_plural'        => 'blocks',
    'block_type'          => 'block type',    // Similar to content types, each block is given a type to distinguish it's unique features.
    'block_type_plural'   => 'block types',
    'field'               => 'field',         // Fields are used to create content/block types. For example, a text 'field' could be added to a content type to provide a heading.
    'field_plural'        => 'fields',
    'scaffolding'         => 'scaffolding',   // Encapsulates content/block types and fields. 'Scaffolding' menu items are provided to users who have the ability to build the structure of the website
    'settings'            => 'settings',      // Title for section to configure settings for the CMS
    'user'                => 'user',          // Encapsulates all user options and settings
    'user_plural'         => 'users',
    'role'                => 'role',          // Users can be assigned 'roles' or groups, which provide a set of permissions to the user
    'role_plural'         => 'roles',
    'permission'          => 'permission',    // Permissions are delegated to roles to allow access to specified areas and features of the CMS
    'permission_plural'   => 'permissions',
    'quicklink'           => 'Quick Link',    // Refers to pages/areas of the CMS which are bookmarked/favourited for the user
    'quicklink_plural'    => 'Quick Links',
];
