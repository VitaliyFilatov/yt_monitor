<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_PatternVideo extends Model_PatternChild
{
	protected $_table_name = 'pattern_video';
	protected $_primary_key = 'id';
	protected $_belongs_to = array(
		'pattern' => array(
			'model' => 'Pattern',
			'foreign_key' => 'pattern_id',
			),
		);
	
	protected $foreign_key = 'pattern_id';
	
	
	
}
