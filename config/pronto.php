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
    
    /**
     * Application logo file path
     */
    'logo' => env('PRONTO_APP_LOGO', 'logo.png'),
    
    /**
     * Application UI theme
     */
    'theme' => env('PRONTO_APP_THEME', 'default'),
    
    
    /** 
     * Footer configuration section
     */
    'footer' => [
        'copyright_holder' => env('PRONTO_COPYRIGHT_HOLDER', 'someone'),
        'start_year' => !!env('PRONTO_COPYRIGHT_START_YEAR', false),
        'tag_line' => env('PRONTO_FOOTER_TAG_LINE', 'Built with <span class="love">♥</span> and <a href="https://github.com/avvertix/pronto-cms/">Pronto</a>'),
    ]

];