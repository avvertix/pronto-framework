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
    
    function subpages_bad_args_provider(){
        
        return [
            [null, 1, ['welcome-to-pronto']],
            ['', 1, ['welcome-to-pronto']],
        ];
        
    }
    
    function subpages_provider(){
        
        return [
            ['section-1/', 3, ['section-1/', 'section-1/other-page', 'section-1/sub-1/']],
            ['section-1', 3, ['section-1/', 'section-1/other-page', 'section-1/sub-1/']],
            ['section-1/sub-1/', 2, ['section-1/sub-1/', 'section-1/sub-1/sub-section']],
            ['section-1/sub-1', 2, ['section-1/sub-1/', 'section-1/sub-1/sub-section']],
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
        
        $this->assertEquals(["index.md", "section-1".DIRECTORY_SEPARATOR."index.md"], $elementPaths);
        
    }
    
    /**
     * Test if the ToC of a section is returned correctly
     * @dataProvider subpages_provider
     */
    public function testGetPages($parent, $expected_count, $expected_slugs)
    {
        $sections = content()->pages($parent);
        
        // var_dump($sections);
        
        $this->assertInstanceOf('Illuminate\Support\Collection', $sections);
        
        $this->assertContainsOnlyInstancesOf('Pronto\Content\PageItem', $sections->all());
        
        $this->assertEquals($expected_count, $sections->count());
    }
    
    /**
     * Test if the ToC of a section is returned correctly
     * @dataProvider subpages_bad_args_provider
     * @expectedException InvalidArgumentException
     */
    public function testGetPagesInvalidArgument($parent, $expected_count, $expected_slugs)
    {
        $sections = content()->pages($parent);
    }
    
    /**
     * Test if the PageItem methods for the content and metadata behave as expected
     */
    public function testGetSinglePage()
    {
        $page = content()->page( 'welcome-to-pronto' );
        
        $this->assertInstanceOf('Pronto\Content\PageItem', $page);
        
        $this->assertEquals('Welcome to Pronto', $page->title());
        $this->assertEquals('welcome-to-pronto', $page->slug());
        $this->assertEquals(true, $page->is_section_home());
        $this->assertEquals(0, $page->level());
        $this->assertEquals('/welcome-to-pronto', $page->path());
        $this->assertEquals(0, $page->order());
        $this->assertEquals('Welcome', $page->metadata('TOCTitle'));
        $this->assertEquals('This is Pronto, the CMS almost "ready".', $page->metadata('MetaDescription'));
        $this->assertEquals('pronto, cms', $page->metadata('MetaTags'));
        $this->assertEquals('<p>This is the page <strong>static text</strong></p>', $page->toHtml());
        
    }
    
    
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
