<?php

use Pronto\Content\Content;
use Pronto\Markdown\Parser;

class ParserServiceTest extends TestCase
{
    /**
     * Test 
     *
     * @return void
     */
    public function testParserInstance()
    {
        
        $service = app(Parser::class);
        
        $this->assertInstanceOf(Parser::class, $service);
        
    }
    
    
    public function testParserForFileContentWithFrontMatter()
    {
      
        $service = app(Parser::class);
        
        $elaborated = $service->file( __DIR__ . '/storage/en/index.md');
        
        $this->assertNotNull($elaborated);
        
        $this->assertEquals('<p>This is the page <strong>static text</strong></p>', $elaborated);

    }
    
    public function testParseFrontMatter()
    {
      
        $service = app(Parser::class);
        
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
      
        $service = app(Parser::class);
        
        $elaborated = $service->text('**bold**');
        
        $this->assertNotNull($elaborated);
        
        $this->assertEquals('<p><strong>bold</strong></p>', $elaborated);

    }
    
    public function testParserForFileContent()
    {
        
        $service = app(Parser::class);
        
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
        
        $service = app(Parser::class);
        
        $service->file(__DIR__ . '/casual.md');
        
    }
}
