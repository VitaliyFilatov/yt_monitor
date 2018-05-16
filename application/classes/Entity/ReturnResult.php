<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ����������� ����� ��� �������� ������-������
 * @author filatov
 *
 */

class Entity_ReturnResult
{
	/**
	 * retun type
	 *
	 * @var int
	 */
	public $return_type = 0;
	
	/**
	 * result
	 *
	 * @var mixed
	 */
	public $result = '';
	
	public function __construct($return_type, $result)
	{
		$this->return_type = $return_type;
		$this->result = $result;
	}
	
	
}