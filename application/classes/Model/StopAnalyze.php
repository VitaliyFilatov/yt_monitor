<?php defined('SYSPATH') or die('No direct script access.');

class Model_StopAnalyze extends Model_Base
{
	protected $_table_name = 'stopanalyze';
	
	protected static $model = 'StopAnalyze';
	
	public static function init($sessionid)
	{
		$sql = "SELECT EXISTS (SELECT * FROM `yt_monitor`.`stopanalyze`
                WHERE `sessionid` = :sessionid) as exist;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array(NULL, 'exist')[0];
		
		if($result == 0)
		{
			$sql = "INSERT INTO `yt_monitor`.`stopanalyze`
				(`sessionid`, `stop`) VALUES (:sessionid, false);";
			$query = DB::query(Database::DELETE, $sql);
		}
		else 
		{
			$sql = "UPDATE `yt_monitor`.`stopanalyze`
				SET `stop`= false
				WHERE `sessionid`=:sessionid;";
			$query = DB::query(Database::UPDATE, $sql);
		}
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}

// 	public static function init($sessionid)
// 	{
// 		$sql = "DELETE FROM `yt_monitor`.`stopanalyze`
// 				WHERE `sessionid` rlike(:sessiontoken);";
// 		$query = DB::query(Database::DELETE, $sql);
// 		$sessiontoken = substr($sessionid, 0, 26);
// 		$query->param(':sessiontoken', $sessiontoken);
// 		$query->execute();
		
		
// 		$sql = "INSERT INTO `yt_monitor`.`stopanalyze`
// 					(`sessionid`, `stop`) VALUES (:sessionid, false);";
// 		$query = DB::query(Database::DELETE, $sql);
// 		$query->param(':sessionid', $sessionid);
// 		$query->execute();
		
// 	}
	
	public static function stop($sessionid)
	{
		$sql = "UPDATE `yt_monitor`.`stopanalyze`
				SET `stop`= true
				WHERE `sessionid`=:sessionid;";
		$query = DB::query(Database::UPDATE, $sql);
		
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function stopAllSessionProcess($session)
	{
		$regexp = $session . '*';
		$sql = "UPDATE `yt_monitor`.`stopanalyze`
				SET `stop`= true
				WHERE `sessionid` regexp(:regexp);";
		$query = DB::query(Database::UPDATE, $sql);
		
		$query->param(':regexp', $regexp);
		$query->execute();
	}
	
	
	public static function isStop($sessionid)
	{
		$sql = "SELECT `stop` FROM `yt_monitor`.`stopanalyze`
                WHERE `sessionid` = :sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		return $query->execute()->as_array(NULL, 'stop')[0];
	}
	
}
