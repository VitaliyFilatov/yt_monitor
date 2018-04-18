<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Pattern extends Model_Base
{
	protected $_table_name = 'pattern';
	
	protected static $tablename = 'pattern';
	protected $_primary_key = 'id';
	
	protected $_has_many = array(
        'video' => array(
            'model' => 'PatternVideo',
            'foreign_key' => 'pattern_id'
        ),
        'words' => array(
            'model' => 'PatternWords',
            'foreign_key' => 'pattern_id'
        )
    );
	
	protected static $model = 'Pattern';
	
	
	public static function deleteByName($name)
	{
	    $sql = "SELECT `id` FROM `pattern`
                WHERE `pattern`.`name` = :name;";
	    $query = $query = DB::query(Database::SELECT, $sql);
	    $query->param(':name', $name);
	    $id = $query->execute()->as_array(NULL, 'id');
	    if(count($id) == 0)
	    {
	        return true;
	    }
	    $id = $id[0];
	    
	    $video = new Model_PatternVideo();
	    $words = new Model_PatternWords();
	    $video->deleteByPatternId($id);
	    $words->deleteByPatternId($id);
	    static::deleteById($id);
	    
	    return true;
	}
	
	public static function deletePatternById($id)
	{
		try 
		{
			$video = new Model_PatternVideo();
			$words = new Model_PatternWords();
			$video->deleteByPatternId($id);
			$words->deleteByPatternId($id);
			static::deleteById($id);
			return true;
		}
		catch(Exception $e)
		{
			Kohana::$log->add(Log::ERROR, "error in db: " . $e->getMessage());
			return "database error";
		}
	}
}
