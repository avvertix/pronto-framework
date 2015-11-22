<?php 

namespace Pronto\Contracts;

/**
 * Can be used in a menu
 */
interface Menuable extends Linkable, Titleable {
	
	/**
	 * True if this is a group of elements
	*/
	function is_group();
	
}