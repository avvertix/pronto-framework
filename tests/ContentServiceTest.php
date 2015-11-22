<?php

use Pronto\Content\Content;

class ContentServiceTest extends TestCase
{
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
    
    
    
    /**
     * Test the retrieval of the global navigation menu
     *
     * @return void
     */
    public function testGetMenu()
    {
        
        $content_service = content()->menu();
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $content_service);
        
        $this->assertEquals(3, $content_service->count());
        
        $this->assertContainsOnlyInstancesOf('Pronto\Content\MenuItem', $content_service->all());
        $this->assertContainsOnlyInstancesOf('Pronto\Contracts\Menuable', $content_service->all());
        
        // TODO: test items are equal to given menu items
    }
    
    public function testGetSections()
    {
        $sections = content()->sections();
        
        // var_dump($sections);
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $sections);
        
        $this->assertContainsOnlyInstancesOf('Pronto\Content\SectionItem', $sections->all());
        
        $this->assertEquals(1, $sections->count());
        
        
        $sections2 = content()->sections('example-section');
        
        // var_dump($sections2);
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $sections2);
        
        $this->assertEquals(2, $sections2->count());
    }
    
    public function testGetSectionMenu()
    {
        $menu = content()->section_menu('example-section');
        
        // var_dump($menu);
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $menu);
        
        $this->assertEquals(3, $menu->count());
        
        $this->assertContainsOnlyInstancesOf('Pronto\Contracts\Menuable', $menu->all());
        
    }
    
    public function testGetPage()
    {
        $page = content()->page('index');
        
        $this->assertInstanceOf('Pronto\Content\PageItem', $page);
        
        $this->assertEquals('Index', $page->title());
        $this->assertEquals('index', $page->slug());
        $this->assertEquals('/index', $page->path());
        
        
        $page = content()->page('index.md', 'example-section');
        
        $this->assertInstanceOf('Pronto\Content\PageItem', $page);
        
        $this->assertEquals('Index', $page->title());
        
        
        $page = content()->page('page-1-1.md', 'example-section/sub-section-1/');
        
        $this->assertInstanceOf('Pronto\Content\PageItem', $page);
        
        $this->assertEquals('Page 1 1', $page->title());
        $this->assertEquals('page-1-1', $page->slug());
        $this->assertEquals('example-section/sub-section-1/page-1-1', $page->path());
        
        $page = content()->page('page-1-1', 'example-section/sub-section-1');
        
        $this->assertInstanceOf('Pronto\Content\PageItem', $page);
        
    }
    
    /**
     * @expectedException Pronto\Exceptions\PageNotFoundException
     */
    public function testGetPageNotFound()
    {
        $page = content()->page('non-existing-file');
    }
}
