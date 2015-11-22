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
