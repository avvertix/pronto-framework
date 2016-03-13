<?php

namespace Pronto\Markdown;

use ParsedownExtra;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Yaml\Yaml;

class Parser
{
    
    const FRONT_MATTER_REGEXP = '/^(= yaml =|---)$([\\s\\S\\R]*?)^(= yaml =|---)$/m';
	
	private $instance = null;
	
	function __construct(ParsedownExtra $parserInstance){
		$this->instance = $parserInstance;
	}
	
    /**
     * Convert the Markdown text to HTML
     */
	public function text($text){
        
        $text = preg_replace('~(*BSR_ANYCRLF)\R~', "\n", $text);
        
        preg_match( self::FRONT_MATTER_REGEXP, $text, $matches );
        
        if(isset($matches[0])){
            
            $text = str_replace($matches[0], '', $text);
            
        }
        
		return $this->instance->text(trim($text));
         
	}
	
    /**
     * Read the file content and converts it from Markdown to HTML
     */
	public function file($path){
		
		if(!@file_exists($path) && !@is_file($path)){
			throw new FileNotFoundException();
		}
		
		return $this->text(@file_get_contents($path));
	}
    
    
    public function frontmatter($text_or_path){
        
        if(@file_exists($text_or_path) && @is_file($text_or_path)){
			$text_or_path = @file_get_contents($text_or_path);
		}
        
        $text_or_path = preg_replace('~(*BSR_ANYCRLF)\R~', "\n", $text_or_path);
        
        preg_match( self::FRONT_MATTER_REGEXP, $text_or_path, $matches );
        
        if(isset($matches[2])){
            
            try {
            
               return Yaml::parse(trim($matches[2]));
            
            } catch (\Exception $e) {
                return [];
            }
            
        }
        else {
            return []; // empty array, no frontmatter found
        }

    }

}