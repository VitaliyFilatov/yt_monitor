<?php
('SYSPATH') or die('No direct script access.');

/**
 * PatternWords
 *
 * @author filatov
 *        
 */
class Entity_PatternWords extends Entity_Abstract
{


    /**
     * Pattern
     *
     * @var Pattern
     */
    public $pattern = '';
    
    /**
     * word
     *
     * @var string
     */
    public $word = array();
    
    /**
     * frequency
     *
     * @var double
     */
    public $frequency = array();
    
    /**
     * dif_frequency
     *
     * @var double
     */
    public $difFrequency = array();	
    
    

    
    
    

    protected static $modelname = 'PatternWords';

    public function __construct($id)
    {
        parent::__construct($id);
        if ($this->model()->loaded()) {
        	
        	if ($this->model()->pattern_id)
                $this->pattern = new Pattern($this->model()->pattern_id);
            
                $this->word = $this->model()->word;
                $this->frequency = $this->model()->frequency;
                $this->difFrequency = $this->model()->dif_frequency;
        } else
        	throw new Exception();
            //throw new Exception_DB(Exception_DB::NOT_FOUND);
    }
}
