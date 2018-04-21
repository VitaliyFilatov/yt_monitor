<?php defined('SYSPATH') or die('No direct script access.');

class Model_PauseAnalyze extends Model_Base
{
	protected $_table_name = 'pauseanalyze';
	
	protected static $model = 'PauseAnalyze';
	
	public static function init($sessionid)
	{
		$sql = "SELECT EXISTS (SELECT * FROM `yt_monitor`.`pauseanalyze`
                WHERE `sessionid` = :sessionid) as exist;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array(NULL, 'exist')[0];
		
		if($result == 0)
		{
			$sql = "INSERT INTO `yt_monitor`.`pauseanalyze`
				(`sessionid`, `pause`) VALUES (:sessionid, false);";
			$query = DB::query(Database::DELETE, $sql);
		}
		else
		{
			$sql = "UPDATE `yt_monitor`.`pauseanalyze`
				SET `pause`= false
				WHERE `sessionid`=:sessionid;";
			$query = DB::query(Database::UPDATE, $sql);
		}
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function pause($sessionid)
	{
		$sql = "UPDATE `yt_monitor`.`pauseanalyze`
				SET `pause`= true
				WHERE `sessionid`=:sessionid;";
		$query = DB::query(Database::UPDATE, $sql);
		
		$query->param(':sessionid', $sessionid);
		$query->execute();
	}
	
	public static function isPause($sessionid)
	{
		$sql = "SELECT `pause` FROM `yt_monitor`.`pauseanalyze`
                WHERE `sessionid` = :sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		return $query->execute()->as_array(NULL, 'pause')[0];
	}
}
