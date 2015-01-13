<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('google_lat_long'))
{
	function google_lat_long($address)
	{
		$address = str_replace(" ", "+",$address);

		$url = urlencode("http://maps.google.com/maps/api/geocode/xml?address=$address&sensor=false");
		$xml = simplexml_load_file($url);
		
		if ($xml == FALSE)
			return NULL;

		if ($xml->status != "OK")
			return NULL;

		return array (
			floatval($xml->result[0]->geometry->location->lat),
			floatval($xml->result[0]->geometry->location->lng));
	}
}
