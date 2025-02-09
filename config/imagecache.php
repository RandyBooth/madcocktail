<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    | 
    | {route}/{template}/{filename}
    | 
    | Examples: "images", "img/cache"
    |
    */
   
    'route' => 'img/cache',

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submited 
    | by URI. 
    |
    | Define as many directories as you like.
    |
    */
    
    'paths' => array(
        public_path('storage/upload_images/'),
        public_path('upload_site'),
        public_path('storage/trash_images/'),
    ),

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates 
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */
   
    'templates' => array(
        'lists' => 'App\Filters\Image\Lists',
        'lists-tiny' => 'App\Filters\Image\ListsTiny',
        'share' => 'App\Filters\Image\Share',
        'single' => 'App\Filters\Image\Single',
        'single-tiny' => 'App\Filters\Image\SingleTiny',
        'user-normal' => 'App\Filters\Image\UserNormal',
        'user-profile' => 'App\Filters\Image\UserProfile',
        'user-small' => 'App\Filters\Image\UserSmall',
//        'small' => 'App\Filters\Image\Small',
//        'medium' => 'App\Filters\Image\Medium',
//        'large' => 'App\Filters\Image\Large',
    ),

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
    */
   
    'lifetime' => 43200,

);
