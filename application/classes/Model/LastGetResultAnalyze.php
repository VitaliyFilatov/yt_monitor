<?php defined('SYSPATH') or die('No direct script access.');

class Model_LastGetResultAnalyze extends Model_Base
{
	protected $_table_name = 'lastgetresultanalyze';
	
	protected static $model = 'LastGetResultAnalyze';
	
// 	public static function init($sessionid)
// 	{
// 		$sql = "DELETE FROM `yt_monitor`.`lastgetresultanalyze`
// 				WHERE `sessionid`=:sessionid";
// 		$query = DB::query(Database::DELETE, $sql);
// 		$query->param(':sessionid', $sessionid);
// 		$query->execute();
// 	}
	
	public static function init($sessionid)
	{
		$sql = "DELETE FROM `yt_monitor`.`lastgetresultanalyze`
				WHERE `sessionid` rlike(:sessiontoken);";
		$query = DB::query(Database::DELETE, $sql);
		$sessiontoken = substr($sessionid, 0, 26);
		$query->param(':sessiontoken', $sessiontoken);
		$query->execute();
	}
	
	public static function updateTimestamp($sessionid)
	{
		$sql = "INSERT INTO `yt_monitor`.`lastgetresultanalyze`
				(`sessionid`, `lasttimestamp`) VALUES(:sessionid, :lasttimestamp)
				ON DUPLICATE KEY UPDATE `lasttimestamp`= VALUES(`lasttimestamp`)";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':sessionid', $sessionid);
		$date = new DateTime();
		$query->param(':lasttimestamp', $date->getTimestamp());
		$query->execute();
	}
	public static function getLastTimestamp($sessionid)
	{
		$sql = "SELECT `lasttimestamp` FROM `yt_monitor`.`lastgetresultanalyze`
                WHERE `sessionid` = :sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		$result = $query->execute()->as_array(NULL, 'lasttimestamp');
		if(!empty($result))
		{
			return $result[0];
		}
		return null;
	}
}
