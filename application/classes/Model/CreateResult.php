<?php defined('SYSPATH') or die('No direct script access.');

class Model_CreateResult extends Model_Base
{
	protected $_table_name = 'create_result';
	
	protected static $model = 'CreateResult';
	
	public static function init($sessionid)
	{
		$sql = "DELETE FROM `yt_monitor`.`create_result`
				WHERE `sessionid`=:sessionid";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function addResult($sessionid, $process)
	{
		$sql = "SELECT EXISTS (SELECT * FROM `yt_monitor`.`create_result`
                WHERE `sessionid` = :sessionid) as exist;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array(NULL, 'exist')[0];
		
		if($result == 0)
		{
			$sql = "INSERT INTO `yt_monitor`.`create_result`
				(`sessionid`, `process`)
				VALUES(:sessionid, :process)";
			$query = DB::query(Database::INSERT, $sql);
		}
		else
		{
			$sql = "UPDATE `yt_monitor`.`create_result`
				SET `process`=:process
				WHERE `sessionid`=:sessionid;";
			$query = DB::query(Database::UPDATE, $sql);
		}
		
		$query->param(':sessionid', $sessionid);
		$query->param(':process', $process);
		$query->execute();
	}
	
	public static function popResult($sessionid)
	{
		$sql = "SELECT * FROM `yt_monitor`.`create_result`
                WHERE `sessionid` = :sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array();
		
		if(empty($result))
		{
			return null;
		}
		$result = $result[0];
		$sql = "DELETE FROM `yt_monitor`.`create_result`
				WHERE `id`=:id";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':id', $result['id']);
		$query->execute();
		
		return $result['process'];
	}
}
