<?php defined('SYSPATH') or die('No direct script access.');

abstract class Model_BagWord extends Model_Base
{
	public static function getCountUniqWords()
	{
		$sql = "SELECT count(*) as `count` FROM ". static::$tablename .";";
		$query = DB::query(Database::SELECT, $sql);
		return $query->execute()->as_array(NULL, 'count')[0];
	}
	
	public static function getSumFreq()
	{
		$sql = "SELECT count(freq) as `sum` FROM ". static::$tablename .";";
		$query = DB::query(Database::SELECT, $sql);
		return $query->execute()->as_array(NULL, 'sum')[0];
	}
	
	public static function getFreqByWord($word)
	{
		$sql = "SELECT freq FROM ". static::$tablename ." where word=:word;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':word', $word);
		$result = $query->execute()->as_array(NULL, 'freq');
		if(empty($result))
		{
			return 0;
		}
		return $result[0];
	}
}
