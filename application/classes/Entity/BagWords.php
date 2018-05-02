<?php
('SYSPATH') or die('No direct script access.');

/**
 * Pattern
 *
 * @author filatov
 *
 */
class Entity_BagWords extends Entity_Abstract
{
	
	
	/**
	 * слово
	 *
	 * @var string
	 */
	public $word = '';
	
	/**
	 * частота слова
	 *
	 * @var string
	 */
	public $freq = '';
	
	public function __construct($id=null)
	{
		if($id === null)
		{
			return;
		}
		parent::__construct($id);
		if ($this->model()->loaded()) {
			$this->word = $this->model()->word;
			$this->freq = $this->model()->freq;
		} else
			throw new Exception();
	}
}
