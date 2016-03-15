<?php

namespace Pronto\Content;

use Pronto\Contracts\Content as ContentContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

use Pronto\Exceptions\InvalidMenuItemException;
use Pronto\Exceptions\PageNotFoundException;

class Content implements ContentContract
{
	
	/**
     * The filesystem service instance
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem = null;
    
    /**
     * The path where the site content (i.e. the markdown files) is stored
     *
     * @var string
     */
    private $storage_path = '.';
    
    
    private $config = null;
    
    /**
     * Current content language
     *
     * @var string
     */
    private $language = 'en';
    
    
	function __construct($config, Filesystem $fileSystemService){
        
		$this->filesystem = $fileSystemService;
        
        $this->storage_path = $config['content_folder'];
        
        $this->language = $config['default_language'];
        
        $this->config = $config;
        
	}
    
    
    // Language methods ---------------------
    
    function available_languages(){
        
        $langs = $this->filesystem->directories($this->storage_path);
        
        $langs = array_map(function($l){
            return basename($l);
        }, $langs);
        
        return $langs;
        
    }
    
    function current_language(){
        
        return $this->language;
        
    }
    
    function set_current_language( $language ){
        
        $this->language = $language;
        
        return $this;
        
    }
    
    
    // Navigation methods -------------------
	
    /**
     * Get the global navigation menu.
     * 
     * Returns the global website navigation menu composed by first-level pages that are section 
     * homes (index.md), 0-level pages and menu items grabbed from the config
     *
     * @return Collection<PageItem>
     */
    function global_navigation(){
        
        $items = array();
        
        $collection = $this->_all();
        
        // TODO: grab menu items from the config
        
        
        $collection = $collection->filter(function($v, $k){
            return $v->level() === 0 || ( $v->level() == 1 && $v->is_section_home() );
        });
        
        $menu = $collection->sortBy(function($v){
            return $v->order();
        });
        
        return $menu;
        
    }
    
    
    // Page retrieval methods ---------------
	
    /**
     * return the pages that belongs to a specified section, i.e. are child of a PageItem->is_section_home() page
     * empty or null $parent will cause to return 
     */
	function pages($parent){
        
        if(is_null($parent) || empty($parent)){
            throw new \InvalidArgumentException('You must specify a parent path in the form one/two/');
        }
        
        $collection = $this->_all();
        
        // current level of the parent specified, based on how many slash it contains
        $level = count( array_filter( explode( '/', $parent ) ) );
        
        
        // Page that has level >= level_of($parent_slug)
        return $collection->filter(function($v, $k) use($parent, $level) {
                        
            $path = $v->path();
            $path_slash = ends_with($path, '/') ? $path : $path.'/';
            
            $condition = $v->level() === $level && ( starts_with($path, $parent) || starts_with($path_slash, $parent) );
            $condition2 = $v->level() === $level+1 && $v->is_section_home() && ( starts_with($path, $parent) || starts_with($path_slash, $parent) );
            
            return $condition || $condition2;
        });
        
    }
    
    /**
     * Finds a page by its slug or full URL path
     */
    function page($slug){
        
        $all = $this->_all();
        
        $page = $all->first(function($k, $v) use($slug) {
            
            $path = $v->path();
            $path_slash = ends_with($path, '/') ? $path : $path.'/';
            
            return $v->slug() === $slug || $v->path() === $slug || ($v->path().'/') === $slug;
            
        });
        
        if(is_null($page)){
            throw new PageNotFoundException($slug);
        }
        
        return $page;
        
    }
    
    function homepage(){
        
        $all = $this->_all();
        
        $page = $all->first(function($k, $v) {
            
            return $v->is_homepage();
            
        });
        
        if(is_null($page)){
            throw new PageNotFoundException('The home page has not been found');
        }
        
        return $page;
        
    }


    // Internal magic -----------------------
    
    /**
     * Retrieve all the content with respect to the current_language()
     *
     * @returns Collection<PageItem>
     */
    private function _all(){
        
        $directory = $this->storage_path . '/' . $this->language . '/';
        
        $finder = Finder::create()->in($directory)->files();
        
        $sections = array();
        
        foreach ($finder as $file) {
            
            $sections[] = PageItem::make($file, $this->language);
            
        }
        
        return Collection::make($sections);
        
    }
 
 
 
    // Static utility methods ---------------
    
    /**
     * Convert a string to slug
     *
     * @param string $str The string to transform
     * @returns string The slug version of $str
     */
    public static function str_to_slug($str){
        $str = str_replace('.md', '', $str);
        return str_slug($str);
    } 
    
    /**
     * Convert a slug to a normal string
     *
     * @param string $slug The slug
     * @returns string The restored normal version of $slug
     */
    public static function slug_to_str($slug){
        return ucwords(str_replace('_', ' ', str_replace('-', ' ', $slug)));
    }
    
    /**
     * Convert a file name to a title 
     *
     * @param string $str The name of the file
     * @returns string The title of the file that was the result of $str transformation
     */
    public static function filename_to_title($str){
        $str = str_replace('.md', '', $str);
        return ucwords(str_replace('_', ' ', str_replace('-', ' ', str_slug($str))));
    }

}