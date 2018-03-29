<?php defined('SYSPATH') or die('No direct script access.');

class Model_Base extends ORM {
    
    protected static $model;
    
    protected static $modelname;
    
    
    
    public static function deleteById($id)
    {
        $obj = ORM::factory(static::$model, $id);
        $obj->delete();
    }
}