<?php

namespace Pronto\Content;

use Symfony\Component\Finder\SplFileInfo;
use Pronto\Content\Content;
use Pronto\Contracts\Menuable;

/**
 * Describe a Page
 */
class PageItem implements Menuable
{

	private $title = null;
	private $slug = null;
	private $path = null;
	private $file = null;

	/**
	 * __construct 
	 */
	protected function __construct(SplFileInfo $file, $relativePath = null){
		$this->title = Content::filename_to_title($file->getFilename());
		$this->slug = Content::str_to_slug($file->getFilename());
		
		$relativePath = (!is_null($relativePath) && !empty($relativePath) ? $relativePath : $file->getRelativePath());
		$this->path =  $relativePath . (ends_with($relativePath, '/') ? '' : '/') . $this->slug;
		 
		$this->file = $file;
	}

	/**
	 * The page title
	 *
	 * @return string
	 */
	public function title(){

		// TODO: get title from meta attribute in the file content if available
		
		return $this->title;
	}
	
	/**
	 * The page path usable for the URL linking
	 *
	 * @return string
	 */
	public function path(){
		return $this->path;
	}
	
	public function link_to(){
		return route('page', ['page' => $this->path]);
	}
	
	/**
	 * The page slug, created from the page title
	 *
	 * @return string
	 */
	public function slug(){
		return $this->slug;
	}
	
	/**
	 * Return the file content
	 *
	 * @return string
	 */
	public function content(){
		return $this->file->getContents();
	}
	
	/**
	 * Get the page featured image, if exists
	 */
	public function featuredimage(){
		return null;
	}
	
	/**
	 * Return the absolute path to the file on the filesystem 
	 *
	 * @return string
	 */
	public function filepath(){
		return $this->file->getRealPath();
	}
	
	function is_group(){
		return false;
	}


	/**
	 * Create a new PageItem instance given a file.
	 *
	 * @param SplFileInfo $file the file to get the information from
	 * @param string $relativePath the optional relative path, if cannot be deduced by the $file argument (default null => $file->getRelativePath() will be used)
	 * @returns PageItem
	 */
	public static function make(SplFileInfo $file, $relativePath = null){
		return new self($file, $relativePath);
	}

}