<?php defined('SYSPATH') or die('No direct script access.');

interface Entity_Interface {
	/**
	 * Возвращает ORM модель
	 * @return ORM
	 */
	public function model();
	
	/**
	 * Возвращает массив объектов класса
	 * @return array
	 */
	static public function dictionary();
}