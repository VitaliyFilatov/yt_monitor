<?php defined('SYSPATH') or die('No direct script access.');

class Model_Result extends Model_Base
{
	protected $_table_name = 'result';
	
	protected static $model = 'Result';
	
	public static function init($sessionid)
	{
		$sql = "DELETE FROM `yt_monitor`.`result`
				WHERE `sessionid`=:sessionid";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function addResult($sessionid, $videoid, $sim)
	{
		$sql = "INSERT INTO `yt_monitor`.`result`
				(`sessionid`, `videoid`, `sim`)
				VALUES(:sessionid, :videoid, :sim)";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':sessionid', $sessionid);
		$query->param(':videoid', $videoid);
		$query->param(':sim', $sim);
		$query->execute();
	}
	
	public static function popResult($sessionid)
	{
		$sql = "SELECT * FROM `yt_monitor`.`result`
                WHERE `sessionid` = :sessionid AND id = (
				SELECT MIN(`id`) FROM `yt_monitor`.`result` WHERE `sessionid`=:sessionid);";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array();
		
		
		if(empty($result))
		{
			return null;
		}
		
		$result = $result[0];
		
		Kohana::$log->add(Log::DEBUG, "id: " . $result['id'] . "videoId: " . $result['videoid']);
		
		$sql = "DELETE FROM `yt_monitor`.`result`
				WHERE `id`=:id";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':id', $result['id']);
		$query->execute();
		
		return $result;
	}
}
