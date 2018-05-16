<?php defined('SYSPATH') or die('No direct script access.');

class Model_Result extends Model_Base
{
	protected $_table_name = 'result';
	
	protected static $model = 'Result';
	
// 	public static function init($sessionid)
// 	{
// 		$sql = "DELETE FROM `yt_monitor`.`result`
// 				WHERE `sessionid`=:sessionid";
// 		$query = DB::query(Database::DELETE, $sql);
// 		$query->param(':sessionid', $sessionid);
// 		$query->execute();
// 	}

	public static function init($sessionid)
	{
		$sql = "DELETE FROM `yt_monitor`.`result`
				WHERE `sessionid` rlike(:sessiontoken);";
		$query = DB::query(Database::DELETE, $sql);
		$sessiontoken = substr($sessionid, 0, 26);
		$query->param(':sessiontoken', $sessiontoken);
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
	
	public static function addResultWithInfo($sessionid, $videoid, $sim, $erpost, $erbyviews, $positiveCount, $negativeCount)
	{
		$sql = "INSERT INTO `yt_monitor`.`result`
				(`sessionid`, `videoid`, `sim`, `erpost`, `erbyviews`, `positive_count`,`negative_count`)
				VALUES(:sessionid, :videoid, :sim, :erpost, :erbyviews, :positive, :negative)";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':sessionid', $sessionid);
		$query->param(':videoid', $videoid);
		$query->param(':sim', $sim);
		$query->param(':erpost', $erpost);
		$query->param(':erbyviews', $erbyviews);
		$query->param(':positive', $positiveCount);
		$query->param(':negative', $negativeCount);
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
		
		$sql = "DELETE FROM `yt_monitor`.`result`
				WHERE `id`=:id";
		$query = DB::query(Database::DELETE, $sql);
		$query->param(':id', $result['id']);
		$query->execute();
		
		return $result;
	}
	
	
	public static function popAllResults($sessionid)
	{
		$sql = "SELECT * FROM `yt_monitor`.`result`
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
		
		$sql = "DELETE FROM `yt_monitor`.`result`
				WHERE `id` IN (" . implode(",",$ids) . ")";
		$query = DB::query(Database::DELETE, $sql);
		$query->execute();
		
		return $results;
	}
}
