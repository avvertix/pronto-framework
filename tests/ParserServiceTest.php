<?php

use Pronto\Content\Content;

class ParserServiceTest extends TestCase
{
    /**
     * Test 
     *
     * @return void
     */
    public function testParserInstance()
    {
        
        $service = app('Pronto\Markdown\Parser');
        
        $this->assertInstanceOf('Pronto\Markdown\Parser', $service);
        
    }
    
    
    public function testParserForFileContentWithFrontMatter()
    {
      
        $service = app('Pronto\Markdown\Parser');
        
        $elaborated = $service->file( __DIR__ . '/storage/en/index.md');
        
        $this->assertNotNull($elaborated);
        
        $this->assertEquals('<p>This is the page <strong>static text</strong></p>', $elaborated);

    }
    
    public function testParseFrontMatter()
    {
      
        $service = app('Pronto\Markdown\Parser');
        
        $elaborated = $service->frontmatter( __DIR__ . '/storage/en/index.md');
        
        $this->assertNotNull($elaborated);
        
        $this->assertEquals([
            'Order' => 0,
            'PageTitle' => 'Welcome to Pronto.',
            'TOCTitle' => 'Welcome',
            'MetaDescription' => 'This is Pronto, the CMS almost "ready".',
            'MetaTags' => 'pronto, cms'
        ], $elaborated);

    }
    
    
    /**
     * Test 
     *
     * @return void
     */
    public function testParserForText()
    {
      
        $service = app('Pronto\Markdown\Parser');
        
        $elaborated = $service->text('**bold**');
        
        $this->assertNotNull($elaborated);
        
        $this->assertEquals('<p><strong>bold</strong></p>', $elaborated);

    }
    
    public function testParserForFileContent()
    {
        
        $service = app('Pronto\Markdown\Parser');
        
        $file_path = __DIR__ . '/test.md';
        
        file_put_contents($file_path, '**bold**');
        
        $elaborated = $service->file($file_path);
        
        unlink($file_path);
        
        $this->assertNotNull($elaborated);
        
        $this->assertEquals('<p><strong>bold</strong></p>', $elaborated);
        
    }
    
    /**
     * @expectedException Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testParserForFileException()
    {
        
        $service = app('Pronto\Markdown\Parser');
        
        $service->file(__DIR__ . '/casual.md');
        
    }
}
