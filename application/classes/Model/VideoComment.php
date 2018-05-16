<?php defined('SYSPATH') or die('No direct script access.');

class Model_VideoComment extends Model_Base
{
	protected $_table_name = 'video_comment';
	
	protected static $model = 'VideoComment';
	
	public static function addComments($videoId, $comments)
	{
		$sql = "INSERT INTO `yt_monitor`.`video_comment`
				(`videoid`, `comment`) VALUES ";
		foreach($comments as $key=>$comment)
		{
			$comm = preg_replace('/[\x{10000}-\x{10FFFF}]/u', "\xEF\xBF\xBD", $comment);
			$sql .= "('$videoId', '$comm')";
			if($key < count($comments) - 1)
			{
				$sql .= ", ";
			}
			else
			{
				$sql .= ";";
			}
		}
		$query = DB::query(Database::INSERT, $sql);
		$query->execute();
	}
	
	public static function getVideoComments($videoId)
	{
		$sql = "SELECT `comment` FROM `yt_monitor`.`video_comment`
                WHERE `videoid` = :videoid;";
		$query = DB::query(Database::SELECT, $sql);
		$query->param(':videoid', $videoId);
		return $query->execute()->as_array(NULL, 'comment');
	}
	
}
