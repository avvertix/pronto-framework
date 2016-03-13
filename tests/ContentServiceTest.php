<?php

use Pronto\Content\Content;

class ContentServiceTest extends TestCase
{
    
    // data providers -----------------------
    
    function pages_provider(){
        
        return [
            ['welcome-to-pronto', 'Welcome to Pronto'],
            ['sub-index', 'Sub Index'],
            ['section-1/', 'Sub Index'],
            ['section-1', 'Sub Index'],
            ['section-1/other-page', 'Other Page'],
            ['section-1/sub-1/', 'Sub 1 Index'],
            ['section-1/sub-1', 'Sub 1 Index'],
            ['section-1/sub-1/sub-section', 'Sub section'],
        ];
        
    }
    
    
    // tests --------------------------------
    
    /**
     * Test content() helper for getting Pront\Contract\Content implementation Pront\Content\Content
     *
     * @return void
     */
    public function testContentHelper()
    {
        
        $content_service = content();
        
        $this->assertInstanceOf('Pronto\Content\Content', $content_service);
        
    }
    
    
    public function testLanguage(){
        
        $this->assertEquals('en', content()->current_language());
        
        content()->set_current_language('it');
        
        $this->assertEquals('it', content()->current_language(), 'Set Language');
        
        $this->assertEquals(["en", "it"], content()->available_languages(), 'Available Languages');
        
    }
    
    
    /**
     * Test the retrieval of the global navigation menu
     *
     * @return void
     */
    public function testGetGlobalNavigationMenu()
    {
        
        content()->set_current_language('it');
        
        $content_service = content()->global_navigation();
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $content_service);
        
        $this->assertEquals(1, $content_service->count());
        
        $elementPaths = $content_service->map(function($r){
            return $r->filepathname();
        })->toArray();
        
        $this->assertEquals(["index.md"], $elementPaths);
        
        content()->set_current_language('en');
        
        $content_service = content()->global_navigation();
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $content_service);
        
        $this->assertEquals(2, $content_service->count());
        
        $this->assertContainsOnlyInstancesOf('Pronto\Contracts\Menuable', $content_service->all());
        
        $elementPaths = $content_service->map(function($r){
            return $r->filepathname();
        })->toArray();
        
        $this->assertEquals(["index.md", "section-1\index.md"], $elementPaths);
        
    }
    
    // public function testGetSections()
    // {
    //     $sections = content()->sections();
        
    //     // var_dump($sections);
        
    //     $this->assertInstanceOf('Illuminate\Support\Collection', $sections);
        
    //     $this->assertContainsOnlyInstancesOf('Pronto\Content\SectionItem', $sections->all());
        
    //     $this->assertEquals(1, $sections->count());
        
        
    //     $sections2 = content()->sections('example-section');
        
    //     // var_dump($sections2);
        
    //     $this->assertInstanceOf('Illuminate\Support\Collection', $sections2);
        
    //     $this->assertEquals(2, $sections2->count());
    // }
    
    // public function testGetSectionMenu()
    // {
    //     $menu = content()->section_menu('example-section');
        
    //     // var_dump($menu);
        
    //     $this->assertInstanceOf('Illuminate\Support\Collection', $menu);
        
    //     $this->assertEquals(3, $menu->count());
        
    //     $this->assertContainsOnlyInstancesOf('Pronto\Contracts\Menuable', $menu->all());
        
    // }
    
    /**
     * @dataProvider pages_provider 
     */
    public function testGetPage($page_slug, $expected_title)
    {
        $page = content()->page( $page_slug );
        
        $this->assertInstanceOf('Pronto\Content\PageItem', $page);
        
        $this->assertEquals($expected_title, $page->title());
    }
    
    /**
     * @expectedException Pronto\Exceptions\PageNotFoundException
     */
    public function testGetPageNotFound()
    {
        $page = content()->page('non-existing-file');
    }
}
