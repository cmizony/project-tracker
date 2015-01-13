<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('secure_hash'))
{
	define ('PREFIX_SALT','a45vsdfDfcasF4yhgf657bds');
	define ('SUFFIX_SALT','asd456fdvb4560gdfsVdfg2a');

	function secure_hash ($str)
	{
		return hash("whirlpool",PREFIX_SALT.$str.SUFFIX_SALT);
	}
}
