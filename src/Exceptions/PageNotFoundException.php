<?php
namespace Pronto\Exceptions;

use Exception;

	
class PageNotFoundException extends Exception {
	
	
	function __construct($page){
		parent::__construct('Page not found: ' . $page, 404);
	}
	
}
	
	