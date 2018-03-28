<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ����������� ����� ��� �������� ������-������
 * @author filatov
 *
 */

abstract class Entity_Abstract implements Entity_Interface
{
	/**
	 * �������������
	 *
	 * @var int
	 */
	public $id = 0;
	
	/**
	 * ORM ������
	 * @var ORM
	 */
	protected $model;
	
	/**
	 * �������� ORM ������ ��� �������� ������-������ ���
	 * @var string
	 */
	protected static $modelname;
	
	/**
	 * ����������� �� ��������� ��� ���� ��������� ������-������ ���
	 * @param int $id
	 */
	public function __construct($id = NULL)
	{
		$this->initModel($id);
		if ($this->model->loaded())
		{
			$this->id = $this->model->id;
		}
	}
	
	/**
	 * {@inheritDoc}
	 * @see LISSubject_Interface::model()
	 */
	public function model()
	{
		return $this->model;
	}
	
	/**
	 * {@inheritDoc}
	 * @see LISSubject_Interface::initModel()
	 */
	private function initModel($id)
	{
		$this->model = ORM::factory(static::$modelname, $id);
	}
	
	static public function dictionary()
	{
		$response = array();
		$items = ORM::factory(static::$modelname)->find_all();
		foreach ($items as $item)
		{
			$response[] = new static($item->id);
		}
		return $response;
	}
	
	
	static public function checkbyfield($value, $filed = "id")
	{
		return ORM::factory(static::$modelname)->checkbyfield($value, $filed);
	}
	
	static public function getrowbyfiled($value, $filed, $searchfield = "id")
	{
		return ORM::factory(static::$modelname)->getrowbyfiled($value, $filed, $searchfield);
	}
}