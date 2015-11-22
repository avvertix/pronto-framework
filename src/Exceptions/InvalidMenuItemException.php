<?php
namespace Pronto\Exceptions;

use Exception;

	
class InvalidMenuItemException extends Exception {
	
	private $raw = null;
	
	function __construct($message, $raw_menu_item){
		parent::__construct($message, 400);
		$this->raw = $raw_menu_item;
	}
	
	
	/**
	 * Get the raw menu item that caused the exception to be raised
	 */
	public function raw(){
		return $this->raw;
	}
	
}
	
	