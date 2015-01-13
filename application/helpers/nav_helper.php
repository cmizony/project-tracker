<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc Simple function to check if current page is equal to nav list element.
 *       This case return "active" as class name for the list element.
 * @param String $pageID
 * @param String $linkID
 * @return String/Null
 */
if ( ! function_exists('is_active'))
{
	function is_active($ID,$linkID)
	{
		if($ID == $linkID){
			return "active";
		}
	}
}

if ( ! function_exists('is_disabled'))
{
	function is_disabled($ID,$linkID)
	{
		if($ID == $linkID){
			return "disabled";
		}
	}
}

if ( ! function_exists('is_selected'))
{
	function is_selected($ID,$linkID)
	{
		if($ID == $linkID){
			return "selected";
		}
	}
}

if ( ! function_exists('is_checked'))
{
	function is_checked($ID,$linkID)
	{
		if($ID == $linkID){
			return "checked";
		}
	}
}
?>
