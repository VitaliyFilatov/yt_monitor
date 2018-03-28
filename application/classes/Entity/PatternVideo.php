<?php
('SYSPATH') or die('No direct script access.');

/**
 * PatternVideo
 *
 * @author filatov
 *        
 */
class Entity_PatternVideo extends Entity_Abstract
{


    /**
     * Pattern
     *
     * @var Pattern
     */
    public $pattern = '';
    
    /**
     * id video
     *
     * @var string
     */
    public $videoId = array(); 
    
    

    
    
    

    protected static $modelname = 'PatternVideo';

    public function __construct($id)
    {
        parent::__construct($id);
        if ($this->model()->loaded()) {
        	
        	if ($this->model()->pattern_id)
                $this->pattern = new Pattern($this->model()->pattern_id);
            $this->videoId = $this->model()->video_id;
        } else
        	throw new Exception();
            //throw new Exception_DB(Exception_DB::NOT_FOUND);
    }
}
