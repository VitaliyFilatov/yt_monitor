<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Pattern extends ORM
{
	protected $_table_name = 'pattern';
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
}
