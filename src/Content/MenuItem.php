<?php

namespace Pronto\Content;

use Pronto\Contracts\Menuable;

class MenuItem implements Menuable
{

	const SECTION = 'section';
	
	const PAGE = 'page';
	
	const LINK = 'link';

	private $title = null;
	private $type = null;
	private $path = null;
	private $level = null;
	private $description = null;


	private function __construct($name, $type, $path, $description = null, $level = 1){
		$this->title = $name;
		$this->type = $type;
		$this->path = $path;
		$this->level = $level;
		$this->description = $description;
	}


	public function title(){
		return $this->title;
	}
	
	public function type(){
		return $this->type;
	}
	
	public function path(){
		return $this->path;
	}
	
	public function level(){
		return $this->level;
	}
	
	public function slug(){
		return str_replace('.md', '', basename($this->path));
	}

	public function link_to(){
		if($this->type === self::LINK){
			return $this->path;
		}
		return route('page', ['page' => $this->path]);
	}
	
	function is_group(){
		return false;
	}


	public static function section($name, $path, $level = 1){
		return new self($name, self::SECTION, $path, $level);
	}
	
	public static function link($name, $path, $level = 1){
		return new self($name, self::LINK, $path, $level);
	}
	
	public static function page($name, $path, $level = 1){
		return new self($name, self::PAGE, $path, $level);
	}

}