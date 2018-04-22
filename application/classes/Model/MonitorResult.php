<?php defined('SYSPATH') or die('No direct script access.');

class Model_MonitorResult extends Model_Base
{
	protected $_table_name = 'monitor_result';
	
	protected static $model = 'MonitorResult';
	
	public static function init($sessionid)
	{
		$sql = "DELETE FROM `yt_monitor`.`monitor_result`
				WHERE `sessionid`=:sessionid";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function addResult($sessionid, $videoid, $sim)
	{
		$sql = "INSERT INTO `yt_monitor`.`monitor_result`
				(`sessionid`, `videoid`, `sim`)
				VALUES(:sessionid, :videoid, :sim)";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':sessionid', $sessionid);
		$query->param(':videoid', $videoid);
		$query->param(':sim', $sim);
		$query->execute();
	}
	
	public static function popAllResults($sessionid)
	{
		$sql = "SELECT * FROM `yt_monitor`.`monitor_result`
                WHERE `sessionid` = :sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$results = $query->execute()->as_array();
		
		
		if(empty($results))
		{
			return null;
		}
		
		$ids = array();
		
		foreach($results as $result)
		{
			array_push($ids, $result['id']);
		}
		
		$sql = "DELETE FROM `yt_monitor`.`monitor_result`
				WHERE `id` IN (" . implode(",",$ids) . ")";
		$query = DB::query(Database::DELETE, $sql);
		$query->execute();
		
		return $results;
	}
}
