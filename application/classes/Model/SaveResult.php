<?php defined('SYSPATH') or die('No direct script access.');

class Model_SaveResult extends Model_Base
{
	protected $_table_name = 'saveresult';
	
	protected static $model = 'SaveResult';
	
	public static function init($sessionid)
	{
		$sql = "DELETE FROM `yt_monitor`.`saveresult`
				WHERE `sessionid`=:sessionid";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function addResult($sessionid, $videoids, $patternId)
	{
		$sql = "INSERT INTO `yt_monitor`.`saveresult`
				(`sessionid`, `videoid`, `patternid`) ";
		foreach($videoids as $key=>$videoid)
		{
			if($key == 0)
			{
				$sql = $sql . "VALUES ('" . $sessionid . "', '" . $videoid . "', " . $patternId . ")";
			}
			else
			{
				$sql = $sql . "('" . $sessionid . "', '" . $videoid . "', " . $patternId . ")";
			}
			if($key != count($videoids) - 1)
			{
				$sql = $sql . ",";
			}
			else
			{
				$sql = $sql . ";";
			}
		}
		$query = DB::query(Database::INSERT, $sql);
		$query->execute();
	}
	
	public static function popAllResults($sessionid)
	{
		$sql = "SELECT * FROM `yt_monitor`.`saveresult`
                WHERE `sessionid` = :sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array();
		
		$sql = "DELETE FROM `yt_monitor`.`saveresult`
				WHERE `sessionid`=:sessionid";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':sessionid', $sessionid);
		$query->execute();
		
		return $result;
	}
}
