<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$this->response->body('hello, world!');
	}

	public function action_test()
	{
		//$trial = ORM::factory('Trialjoin',1);
		//echo $trial->id;
		//echo $trial->uname;
		//echo $trial->trial_table->some_field;
		$pattern = new Pattern(1);
	    $this->response->body($pattern->words[0]["word"]);
	}
	
	public function action_createPattern()
	{
// 	    $pattern = ORM::factory('Pattern');
// 	    $pattern->name = 'Новая категория';
// 	    $pattern->save();
// 	    $video = ORM::factory('PatternVideo');
// 	    $video->video_id = '1234567890';
// 	    $video->pattern_id = $pattern->id;
// 	    $video->save();
// 	    $video = ORM::factory('PatternVideo');
	    
// 	    $video->video_id = '123123';
// 	    $video->pattern_id = $pattern->id;
// 	    $video->save();

	    $servicePattern = new Service_Pattern();
	    
	    $servicePattern->createPattern('Призыв к революции', array('qrUwj-81ycU', 'CDeZRueiR-U'));
// 	    $pattern = ORM::factory('Pattern', 1);
// 	    $videos =  $pattern->video->find_all();
// 	    foreach($videos as $video)
// 	    {
// 	        echo $video->video_id;
// 	    }
	}
	
	public function action_useApi()
	{
	    $request = $this->request;
	    $session = Session::instance();
	    //protected $OAUTH2_CLIENT_ID = '1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com';
	    //protected $OAUTH2_CLIENT_SECRET = 'oMbF7Zj1K9cCVXw3ZVGFN5z-';
	    $result = Service_Pattern::analizeChannel($request, $session);
	    echo $result;
	    
	}
	
	public function action_putToQueue()
	{
	    $MEMSIZE = 512; //  объём выделяемой разделяемой памяти
	    $SEMKEY = 1;   //  ключ семафора
	    $SHMKEY = 2;   //  ключ разделяемой памяти
	    
	    $sem_id = sem_get($SEMKEY, 1);
	    
	    if (! sem_acquire($sem_id))
	    {
	        sem_remove($sem_id);
	        exit;
	    }
	    
	    $shm_id = shm_attach($SHMKEY, $MEMSIZE);
	    if ($shm_id === false)
	    {
	        sem_remove($sem_id);
	        exit;
	    }
	    
	    if (!shm_put_var($shm_id, 1, "msg"))
	    {
	        sem_remove($sem_id);
	        shm_remove($shm_id);
	        exit;
	    }
	    
	    if (!shm_put_var($shm_id, 1, "new"))
	    {
	        sem_remove($sem_id);
	        shm_remove($shm_id);
	        exit;
	    }
	    
	    
	    //shm_remove_var($shm_id, 1);
	    
	    while(shm_has_var ($shm_id, 1)){}
	    
	    sem_release($sem_id);
	    sem_remove($sem_id);
	    shm_remove($shm_id);
	    echo "end";


// 	    $SEMKEY = 1;   //  ключ семафора

// 	    $sem_id = sem_get($SEMKEY, 1);

// 	    if (!sem_acquire($sem_id))
//     	{
//     	    echo "int put sem don't acquire";
//     	}
//     	else
//     	{
//     	    echo "in put sem successful acquire";
//     	}
//     	while (true){}
    	
	    
	}
	
	public function action_getFromQueue()
	{
	    $MEMSIZE = 512; //  объём выделяемой разделяемой памяти
	    $SEMKEY = 1;   //  ключ семафора
	    $SHMKEY = 2;   //  ключ разделяемой памяти
	    
	    
	    $shm_id = shm_attach($SHMKEY, $MEMSIZE);
	    if ($shm_id === false)
	    {
	        exit;
	    }
	    $var = shm_get_var ($shm_id, 1);
	    echo $var;
	    shm_remove_var ($shm_id, 1);
	    shm_remove($shm_id);

//         $SEMKEY = 1;   //  ключ семафора
        
//         $sem_id = sem_get($SEMKEY, 1);
        
//         if (!sem_acquire($sem_id))
//         {
//             echo "int get sem don't acquire";
//         }
//         else
//         {
//             echo "in get sem successful acquire";
//         }
	}
	
	public function action_deletePattern()
	{
	    Service_Pattern::deletePatternByName('Направление');
	}
	
	public function action_getPatternById()
	{
	    Service_Pattern::getPatternById(13);
	}

} // End Welcome
