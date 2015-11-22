<?php

namespace Pronto\Markdown;

use ParsedownExtra;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class Parser
{
	
	private $instance = null;
	
	function __construct(ParsedownExtra $parserInstance){
		$this->instance = $parserInstance;
	}
	
	public function text($text){
		return $this->instance->text($text); 
	}
	
	public function file($path){
		
		if(!@file_exists($path) && !@is_file($path)){
			throw new FileNotFoundException();
		}
		
		return $this->text(@file_get_contents($path));
	}

}