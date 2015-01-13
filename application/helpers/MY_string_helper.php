<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('convert_null'))
{
	function convert_null($string)
	{
		if (empty($string) OR $string == 0)
			return NULL;
		return $string;
	}
}

if ( ! function_exists('humanize_sec'))
{
	function humanize_sec($secs = NULL,$limit = 2)
	{
		if (is_null($secs))
			return;

		$secs = intval($secs);

		$units = array(
			"week"   => 7*24*3600,
			"day"    =>   24*3600,
			"hour"   =>      3600,
			"minute" =>        60,
			"second" =>         1,
		);

		if ( $secs == 0 ) return "0 seconds";

		$s = "";
		$i = 1;

		foreach ( $units as $name => $divisor )
		{
			if ( $quot = intval($secs / $divisor) ) 
			{
				$s .= "$quot $name";
				$s .= (abs($quot) > 1 ? "s" : "") . ", ";
				$secs -= $quot * $divisor;
			}
			if ($i >= $limit)
				break;
			$i++;
		}

		return substr($s, 0, -2);
	}
}

if ( ! function_exists('explode_sec'))
{
	function explode_sec($secs)
	{
		$secs = intval($secs);

		$units = array(
			"week"   => 7*24*3600,
			"day"    =>   24*3600,
			"hour"   =>      3600,
			"min" =>        60
		);

		if ( $secs == 0 ) 
			return array( 0 , "sec");
		
		foreach ( $units as $name => $divisor)
		{
			$quot = $secs / $divisor;
			if ($quot >= 1)
				return (array(number_format($quot,1) , $name));
		}
		
		return (array(number_format($secs/$units["min"],1) , "min"));
	}
}

if ( ! function_exists('format_phone'))
{
	function format_phone($phone)
	{
		$phone = preg_replace("/[^0-9]/", "", $phone);

		if(strlen($phone) == 7)
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
		elseif(strlen($phone) == 10)
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
		else
			return $phone;
	}
}

if ( ! function_exists('format_date'))
{
	function format_date($format,$dateStr) { 
		if (trim($dateStr) == '' || substr($dateStr,0,10) == '0000-00-00') { 
			return NULL; 
		} 
		$ts = strtotime($dateStr); 
		if ($ts === false) { 
			return NULL; 
		} 
		return date($format,$ts); 
	}  
}

if ( ! function_exists('time_ago'))
{
	function time_ago($date)
	{
		$datetime1=new DateTime("now");
		$datetime2=date_create($date);
		$diff=date_diff($datetime1, $datetime2);
		$timemsg='';
		if($diff->y > 0){
			$timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

		}
		else if($diff->m > 0){
			$timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
		}
		else if($diff->d > 0){
			$timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
		}
		else if($diff->h > 0){
			$timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
		}
		else if($diff->i > 0){
			$timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
		}
		else if($diff->s >= 0){
			$timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
		}

		$timemsg = $timemsg.' ago';
		return $timemsg;
	}
}
