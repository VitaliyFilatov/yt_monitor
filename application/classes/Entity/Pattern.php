<?php
('SYSPATH') or die('No direct script access.');

/**
 * Pattern
 *
 * @author filatov
 *        
 */
class Entity_Pattern extends Entity_Abstract
{


    /**
     * имя паттерна
     *
     * @var string
     */
    public $name = '';
    
    /**
     * видео
     *
     * @var PatternVideo[]
     */
    public $video = array(); 
    
    
    /**
     * слова паттерна
     *
     * @var PatternWords[]
     */
    public $words = array();

    
    
    

    protected static $modelname = 'Pattern';

    public function __construct($id)
    {
        parent::__construct($id);
        if ($this->model()->loaded()) {
            
        	$this->video = array();
        	$videos = $this->model()->video->find_all();
            foreach ($videos as $video) {
                array_push($this->video, array("videoid" => $video->video_id));
            }
            
            $this->words = array();
        	$words = $this->model()->words->find_all();
            foreach ($words as $word) {
                array_push($this->words, array("word" => $word->word,
                					   "frequency" => $word->frequency,
                					   "dif_frequency" => $word->dif_frequency));
            }
            
            
            $this->name = $this->model()->name;
        } else
        	throw new Exception();
            //throw new Exception_DB(Exception_DB::NOT_FOUND);
    }
}
