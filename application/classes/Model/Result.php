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
	
	/**
	 * 
	 * @param Entity_VideoInfo $videoInfo
	 */
	public static function addResultWithInfo($sessionid, $videoid, $sim, $videoInfo)
	{
		$sql = "INSERT INTO `yt_monitor`.`result`
				(`sessionid`,
                `videoid`,
                `sim`,
                `like_count`,
                `dislike_count`,
                `positive_count`,
                `negative_count`,
                `view_count`,
                `followers_count`)
				VALUES(:sessionid, :videoid, :sim,
                :likes, :dislikes, :positive,
                :negative, :views, :followers)";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':sessionid', $sessionid);
		$query->param(':videoid', $videoid);
		$query->param(':sim', $sim);
		$query->param(':likes', $videoInfo->like_count);
		$query->param(':dislikes', $videoInfo->dislike_count);
		$query->param(':positive', $videoInfo->positive_count);
		$query->param(':negative', $videoInfo->negative_count);
		$query->param(':views', $videoInfo->view_count);
		$query->param(':followers', $videoInfo->followers_count);
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
