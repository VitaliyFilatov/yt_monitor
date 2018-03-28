<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_PatternWords extends ORM
{
	protected $_table_name = 'pattern_words';
	protected $_primary_key = 'id';
	protected $_belongs_to = array(
		'pattern' => array(
			'model' => 'Pattern',
			'foreign_key' => 'pattern_id',
			),
		);
	
}
