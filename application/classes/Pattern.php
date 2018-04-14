<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Pattern
 *
 * @author filatov
 *        
 */
class Pattern extends Entity_Pattern
{
	
	public static function getAllPatterns()
	{
		$items = Model_Pattern::selectAllRecords();
		$result = array();
		foreach($items as $item)
		{
			array_push($result, new self($item));
		}
		return $result;
	}

}
