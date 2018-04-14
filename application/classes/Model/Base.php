<?php defined('SYSPATH') or die('No direct script access.');

class Model_Base extends ORM {
    
    protected static $model;
    
    protected static $modelname;
    
    protected static $tablename;
    
    public static function selectAllRecords()
    {
    	$sql = "SELECT `id` FROM `" . static::$tablename . "`;";
    	$query = $query = DB::query(Database::SELECT, $sql);
    	return $query->execute()->as_array(NULL, 'id');
    }
    
    public static function deleteById($id)
    {
        $obj = ORM::factory(static::$model, $id);
        $obj->delete();
    }
}