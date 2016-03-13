<?php

class TestCase extends PHPUnit_Framework_TestCase
{
    // /**
    //  * Creates the application.
    //  *
    //  * @return \Laravel\Lumen\Application
    //  */
    // public function createApplication()
    // {
    //     return require __DIR__.'/../bootstrap/app.php';
    // }
    
    // private static $folders = null;
    // private static $files = null;
    
    public static function setUpBeforeClass()
    {
        // $md = 'Pronto with some markdown';
        
//         $json = <<<EOT
//             {
//                 "menu": [
//                     {
//                         "title": "Git",
//                         "ref": "http://your.awesome.git.project"
//                     },
//                     {
//                         "title": "Sub Section Promoted",
//                         "type": "section",
//                         "ref": "example-section/sub-section-1"
//                     },
//                     {
//                         "title": "Home",
//                         "type": "page",
//                         "ref": "index"
//                     }
//                 ]
//             }
// EOT;
//         file_put_contents(__DIR__ . '/../storage/app/config.json', $json);
        
        
        // self::$folders = [
        //     __DIR__ . '/../storage/content/example-section/',
        //     __DIR__ . '/../storage/content/example-section/sub-section-1',
        //     __DIR__ . '/../storage/content/example-section/sub-section-2',
        //     __DIR__ . '/../storage/content/example-section/sub-section-2/sub-sub-section-2-1',
        // ];
        
        // self::$files = [
        //     __DIR__ . '/../storage/content/index.md',
        //     __DIR__ . '/../storage/content/example-section/index.md',
        //     __DIR__ . '/../storage/content/example-section/sub-section-1/page-1-1.md',
        //     __DIR__ . '/../storage/content/example-section/sub-section-1/page-1-2.md',
        //     __DIR__ . '/../storage/content/example-section/sub-section-2/page-2-1.md',
        //     __DIR__ . '/../storage/content/example-section/sub-section-2/page-2-2.md',
        //     __DIR__ . '/../storage/content/example-section/sub-section-2/sub-sub-section-2-1/index.md',
        // ];
        
        // foreach(self::$folders as $folder){
        //     mkdir($folder);
        // }
        
        // foreach(self::$files as $file){
        //     file_put_contents($file, $md);
        // }
        
    }

    public static function tearDownAfterClass()
    {
        // unlink(__DIR__ . '/../storage/app/config.json');
        
        // foreach(self::$files as $file){
        //     unlink($file);
        // }
        
        // foreach(array_reverse(self::$folders) as $folder){
        //     rmdir($folder);
        // }
        
    }
}
