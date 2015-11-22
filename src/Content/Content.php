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
     * The path where the markdown files are stored
     *
     * @var string
     */
    private $storage_path = '.';
    
    private $config = null;
	
	function __construct(Filesystem $fileSystemService){
        
		$this->filesystem = $fileSystemService;
        // dd(config('pronto.content_folder'));
        $this->storage_path = content_path();
        
        $config_path = realpath(storage_path('app/config.json'));
        
        if(@is_file($config_path)){
            $this->config = json_decode(file_get_contents($config_path));
        }
        
	}
	
    /**
      Get the global navigation menu.
      
      return collection of menu items grabbed from the config + first level sections (if enabled in the configuration)
    
    */
    function menu(){
        $items = array();
        
        // grab config menu elements
        if(!is_null($this->config) && property_exists($this->config, 'menu')){
        
            // dd($this->config->menu);
            
            $items = array_merge($items, array_filter(array_map(function($el){
                
                // InvalidMenuItemException
                
                // "title": "Home",
                // "type": "page",
                // "ref": "index.md"
                
                $menu_el = null;
                
                if(!property_exists($el, 'title') || !property_exists($el, 'ref')){
                    throw new InvalidMenuItemException('Menu element should contain at least title and ref attributes', $el);
                } 
                
                if(property_exists($el, 'type') && method_exists(MenuItem::class, $el->type)){
                    $menu_el = MenuItem::{$el->type}($el->title, $el->ref); 
                }
                else {
                    $menu_el = MenuItem::link($el->title, $el->ref);
                }
                
                return $menu_el;
                
            }, $this->config->menu)));
            
        }
        
        // grab first level sections if defined to do so in config 
        if(config('pronto.sections_in_menu', false)){
            // TODO: sections in menu
        }
        
        // return MenuItem collection
        
        return Collection::make($items);
    }
	
    /**
     * return the pages that belongs to a specified section
     */
	function pages($section = null){
        return null;
    }
    
    /**
     * Check if the specified path match to a section
     */
    function is_section($path){
        try {
            $this->page( basename($path), dirname($path));
            return false;
        }catch(PageNotFoundException $pnfe){
            return true;
        }
    }
    
    /**
     * Find a page by its filename or slug. Optionally you can specify the section which contains the page
     */
    function page($name, $section = null){
        
        $directory = $this->storage_path . (!is_null($section) ? DIRECTORY_SEPARATOR . $section : '');

        $name = ends_with($name, '.md') ? $name : $name . '.md';
        
        $finder = Finder::create()->in($directory)->files()->name($name)->depth(0);
        
        $count = iterator_count($finder);
        
        if($count==0){
            throw new PageNotFoundException($name . (!is_null($section) ? ' in ' . $section : ''));
        }
        
        $pg = iterator_to_array($finder, false)[0];
        
        return PageItem::make($pg, $section);
    }
    
    function section($section){
        $directory = $this->storage_path . (!is_null($section) ? DIRECTORY_SEPARATOR . dirname($section) : '');
        
        $name = basename($section);
        
        $finder = Finder::create()->in($directory)->directories()->name($name)->depth(0);
        
        
        
        $count = iterator_count($finder);
        
        if($count==0){
            throw new PageNotFoundException($name . (!is_null($section) ? ' in ' . dirname($section) : ''));
        }
        
        $pg = iterator_to_array($finder, false)[0];
        
        // dd(compact('directory', 'name', 'pg'));
        
        return SectionItem::make($pg, $section);
    }
	
    /**
     * Get the first level sections that could be found in a specific section. 
     * If no section is specified the list of first level section in the content 
     * folder are returned
     */
	function sections($section = null){
        
        // elenco delle section
        
        $directory = $this->storage_path . (!is_null($section) ? '/' . $section : '');
        
        $finder = Finder::create()->in($directory)->directories()->depth(0);
        
        $sections = array();
        
        foreach ($finder as $file) {

            $sections[] = SectionItem::make($file, $section);
            
        }

        return Collection::make($sections);
    }
	
    /**
     * Return the navigation menu for the specified section.
     *
     * The navigation menu consists in sections, pages and other sub-sections.
     *
     * @returns collection of MenuItem
     */
	function section_menu($section){

        /*
            check section parent count w.r.t the content folder
            if more than one show the parent link at least for the first direct parent
        
        
        */
        
        $section_parent = array_values(array_filter(explode('/', $section), function($e){
            return $e !== '.' && !empty($e);
        }));
        
        $section_parent_count = count($section_parent);
        
        $directory = $this->storage_path . (!is_null($section) ? DIRECTORY_SEPARATOR . $section : '');
        
        $finder = Finder::create()->in($directory)->depth("< 1");
        
        $items = array();
        
        if($section_parent_count > 0){
            $parent = $section_parent[$section_parent_count-1];
            
            $items[] = SectionItem::make(new SplFileInfo(realpath($this->storage_path . DIRECTORY_SEPARATOR . $parent), '', ''), dirname($section));
        }
        
        foreach ($finder as $file) {
            
            if($file->isDir()){
                $items[] = SectionItem::make($file, $section);
            }
            else if($file->getFileName() !== 'index.md'){
                $items[] = PageItem::make($file, $section);
            }
            
        }
        
        // SectionItem with child (SectionItem || PageItem)
        // dd(compact('items', 'section', 'section_parent'));
        return Collection::make($items);
    }
    
    /**
     retrieve all the content references
    */
    private function _all(){
        // faccio lo scan ricorsivo di tutto il contenuto nella cartella "content"
    }
    
    
    public static function str_to_slug($str){
        $str = str_replace('.md', '', $str);
        return str_slug($str);
    } 
    
    public static function slug_to_str($slug){
        return ucwords(str_replace('_', ' ', str_replace('-', ' ', $slug)));
    }
    
    public static function filename_to_title($str){
        $str = str_replace('.md', '', $str);
        return ucwords(str_replace('_', ' ', str_replace('-', ' ', str_slug($str))));
    }
    
    
    private function getNavigationMenu(){
        
        // $section_path = $this->storage_path . '/' . $section . '/';
        
        $sections = $this->filesystem->directories($this->storage_path);
        
        // if configuration exists, the configured sections govern the ordering and the sections showed.
        // if in each section the array of pages exists it control what pages are showed and in which order
        
        if(!is_null($this->config) && property_exists($this->config, 'sections')){
            // dd(array('dirs' => $directories, 'config' => $this->config));
            
            // filters directories based on what is in the config
            
            // order directories based on the config
            
            $sections = array_filter(array_map(function($section){
                
                $section->ref = $this->storage_path . '/' . $section->ref;
                
                return @is_dir($section->ref) ? $section : false; // return false in case the check for the directory existence fails
                
            }, $this->config->sections));
        }
        
        // dd($sections);
        
        $menu = array();
        
        $all_files = array();
        foreach ($sections as $sec) {
            
            $dir = is_string($sec) ? $sec : $sec->ref;
            
            $section_ref = basename($dir); 
            
            $name = is_string($sec) ? ucwords(str_replace('_', ' ', str_replace('-', ' ', $section_ref))) : $sec->title;
            
            
            
            // get markdown (md) files in the section or get pages from the configured array of pages
            $all_files = (is_string($sec) && !property_exists($sec, 'pages')) ? $this->filesystem->glob($dir . '/*.md') : array_filter(array_map(function($p) use($dir){
                $file = $dir . '/' . $p;
                return @file_exists($file) && @is_file($file) ? $file : false;
            }, $sec->pages));
            
            $mapped = array_map(function($el) use($dir, $section_ref){
                
                $f = str_replace('.md', '', basename($el));
                
                return array(
                    'name' => ucwords(str_replace('_', ' ', str_replace('-', ' ', $f))),
                    'section' => str_slug($section_ref),
                    'page' => str_slug($f)
                );
                
                // dd($el);
                
            }, $all_files);
            
            
            
            $menu[] = array(
                'name' => $name,
                'child' => $mapped
            );
            
        }
        
        return $menu;
    }


}