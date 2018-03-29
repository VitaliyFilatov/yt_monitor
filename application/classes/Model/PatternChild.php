<?php defined('SYSPATH') or die('No direct script access.');

class Model_PatternChild extends ORM {
    
    protected $foreign_key;
    
    protected function getForeignKey()
    {
        return $this->foreign_key;
    }
    
    
    public function deleteByPatternId($id)
    {
        $sql = "DELETE FROM `". $this->_table_name ."`
                WHERE `" . $this->_table_name . "`.`" . $this->getForeignKey() . "` = :id;";
        $query = $query = DB::query(Database::DELETE, $sql);
        $query->param(':id', $id);
        $query->execute();
    }
}