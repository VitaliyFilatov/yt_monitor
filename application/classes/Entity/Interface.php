<?php defined('SYSPATH') or die('No direct script access.');

interface Entity_Interface {
	/**
	 * ���������� ORM ������
	 * @return ORM
	 */
	public function model();
	
	/**
	 * ���������� ������ �������� ������
	 * @return array
	 */
	static public function dictionary();
}