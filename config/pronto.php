<?php

return [

    /**
     * The Title of the website
     */
    'site_title' => env('SITE_TITLE', 'Pronto.'),
    
    /**
     * The default site meta description to be served to search engines
     */
    'site_meta_description' => env('SITE_META_DESCRIPTION', 'Pronto. The CMS almost "ready".'),
    
    /**
     * The default language of the website
     */
    'default_language' => env('DEFAULT_LANGUAGE', 'en'),

    /**
     * Where the website content is stored
     */
    'content_folder' => env('SITE_CONTENT_FOLDER', storage_path('content') ),
    
    /**
     * Where the additional configuration is stored
     */
    'config_folder' => storage_path('app'),

];