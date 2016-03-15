<?php

namespace Pronto\Contracts;

interface Content
{
    /**
     * Get the global navigation menu.
     *
     * only first level sections and pages are returned.
     */
	function global_navigation();
	
    /**
     * Retrieves all the pages, optionally filters only what is contained in a specific section
     */
	function pages( $parent );
    
    /**
     * Retrieve a page by its slug
     */
    function page($slug);
	
	/**
     * Get the list of languages in which the content is available
     */
    function available_languages();
    
    /**
     * Get the current language of the content
     */
    function current_language();
    
    /**
     * Set the language of the content to be retrieved
     */
    function set_current_language( $language );
    
    /**
     * Get the home page PageItem
     *
     * @return PageItem the home page
     */
    function homepage();
}