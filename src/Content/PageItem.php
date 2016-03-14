<?php

namespace Pronto\Content;

use Symfony\Component\Finder\SplFileInfo;
use Pronto\Content\Content;
use Pronto\Contracts\Menuable;

use Pronto\Markdown\Parser;

/**
 * Describe a Page
 */
class PageItem implements Menuable
{

	private $title = null;
    
	private $slug = null;
	
    private $path = null;
	
    private $file = null;
    
    private $metadata = null;
    
    /**
     * Page Order.
     *
     * null or negative value will make the page disappear from the menu
     */
    private $order = 0;
    
    /**
     * 0 is base level, 1 or more states that is in a sub-folder 
     */
    private $level = 0;

    /**
     * true if page is index.md of a sub-folder
     */
    private $is_section_home = false;    
    
    /**
     * the language of the page (inherited from the content subfolder) 
     */
    private $language = 'en'; // 

	/**
	 * __construct 
	 */
	protected function __construct(SplFileInfo $file, $language = 'en'){
        
        $parser = app(Parser::class);
        
        $this->metadata = $parser->frontmatter($file->getContents());
        
		$this->title = isset($this->metadata['PageTitle']) ? $this->metadata['PageTitle'] : Content::filename_to_title($file->getFilename());

        $this->slug = Content::str_to_slug($this->title);
        
        if(ends_with($file->getRelativePathName(), 'index.md')){
            $this->is_section_home = true;
        }
        
        $this->level = count( array_filter( explode( DIRECTORY_SEPARATOR, $file->getRelativePath() ) ) );
		
		$relativePath = str_replace('\\', '/', $file->getRelativePath());
		
		
        if( $this->level > 0 && $this->is_section_home ){
            $this->path =  $relativePath;
        }
        else {
            $relativePath = $relativePath . (ends_with($relativePath, '/') ? '' : '/');
            $this->path =  $relativePath . $this->slug;
        }
        
        
		
        
        
        
        
        
        $this->language = $language;
        
		$this->file = $file;
        
        $this->order = isset($this->metadata['Order']) ? $this->metadata['Order'] : 0;
        
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
	
    /**
     * @deprecated
     */
	public function link_to(){
		return null;
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
	 * Return the file raw content
	 *
	 * @return string
	 */
	public function rawcontent(){
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
    
    public function filepathname(){
		return $this->file->getRelativePathName();
	}
	
	function is_group(){
		return false;
	}
    
    function is_section_home(){
        return $this->is_section_home;
    }
    
    function level(){
		return $this->level;
	}


	/**
	 * Create a new PageItem instance given a file.
	 *
	 * @param SplFileInfo $file the file to get the information from
	 * @param string $language the language code of the file content
	 * @returns PageItem
	 */
	public static function make(SplFileInfo $file, $language = 'me'){
		return new self($file, $language);
	}

}