<?php defined('SYSPATH') or die('No direct script access.');

class Model_NegativeWord extends Model_BagWord
{
	protected $_table_name = 'negative';
	
	protected static $tablename = 'negative';
	
	protected static $model = 'NegativeWord';
}
