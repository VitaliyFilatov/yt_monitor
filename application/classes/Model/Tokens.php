<?php defined('SYSPATH') or die('No direct script access.');

class Model_Tokens extends Model_Base
{
	protected $_table_name = 'tokens';
	
	protected static $model = 'Tokens';
	
	public static function getToken()
	{
		$sql = "SELECT `token` FROM `yt_monitor`.`tokens`;";
		$query = DB::query(Database::SELECT, $sql);
		$result = $query->execute()->as_array(NULL, 'token');
		if(empty($result))
		{
			return NULL;
		}
		return $result[0];
	}
	
	public static function addToken($token)
	{
		$sql = "INSERT INTO `yt_monitor`.`tokens`
				(`token`) VALUES(:token)";
		$query = DB::query(Database::INSERT, $sql);
		$query->param(':token', $token);
		$query->execute();
	}
	
}
