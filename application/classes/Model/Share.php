<?php defined('SYSPATH') or die('No direct script access.');

class Model_Share extends ORM
{
	
	public static function sharedExist($sessionid)
	{
		$sql = "SELECT EXISTS (SELECT * FROM `shared` WHERE `session_id`=:sessionid) as `exist`;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		if($query->execute()->as_array(NULL, 'exist')[0] == 1)
		{
			return true;
		}
		return false;
	}
	
	public static function createShared($sessionid)
	{
		$sql = "INSERT INTO `shared` (`session_id`) VALUES(:sessionid);";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':sessionid', $sessionid);
		return $query->execute();
	}
	
	public static function getShareId($sessionid)
	{
		$sql = "SELECT `shared_id` FROM `shared` WHERE `session_id`=:sessionid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':sessionid', $sessionid);
		return $query->execute()->as_array(NULL, 'shared_id')[0];
	}
	
}
