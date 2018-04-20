<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	
	public function action_index()
	{
		$this->response->body('hello, world!');
	}

	public function action_test()
	{
		$this->response->body(View::factory('Prepare'));
	}
	
	public function action_analyze()
	{
		$this->response->body(View::factory('Analyze'));
	}
	
	public function action_createPattern()
	{
		$body = $this->request->post();
		$patternName = $body['patternName'];
		$videoIds = $body['videoIds'];
		$videoIds = explode(",",$videoIds);
	    $servicePattern = new Service_Pattern();
	    $sessionid = $body['sessionid'] . 'c';
	    if(Model_Share::sharedExist($sessionid) === false)
	    {
	    	Model_Share::createShared($sessionid);
	    }
	    $sharedid = Model_Share::getShareId($sessionid);
	    try
	    {
	    	$queue = new Service_Queue(512, $sharedid, $sharedid);
	    	$queue->init();
	    	$pattern = $servicePattern->createPattern($patternName, $videoIds, $queue);
	    	echo json_encode($pattern);
	    }
	    catch(Exception $e)
	    {
	    	echo json_encode(array('type'=>0, 'result'=>false));
	    }
	}
	
	public function action_getSubResult()
	{
		$body = $this->request->post();
		$oldValue = $body['oldValue'];
		$sessionid = $body['sessionid'] . 'c';
		if(Model_Share::sharedExist($sessionid) === false)
		{
			Model_Share::createShared($sessionid);
		}
		$sharedid = Model_Share::getShareId($sessionid);
		$queue = new Service_Queue(512, $sharedid, $sharedid);
		$subResult;
		while(true)
		{
			$subResult = $queue->pop(3);
			if($oldValue == 0)
			{
				if($subResult != "101")
				{
					$subResult = 0;
				}
				break;
			}
			if($oldValue == $subResult)
			{
				continue;
			}
			break;
		}
		echo $subResult;
	}
	
	public function action_getSubResultResAnalyze()
	{
		$body = $this->request->post();
		$lastVideoId= $body['lastVideoId'];
		$subResult;
		$sessionid = $body['sessionid'];
		if(Model_Share::sharedExist($sessionid) === false)
		{
			Model_Share::createShared($sessionid);
		}
		$sharedid = Model_Share::getShareId($sessionid);
		$queue = new Service_Queue(512, $sharedid, $sharedid);
		while(true)
		{
			$subResult = $queue->pop(16);
			if($lastVideoId == "")
			{
				if($subResult != "????????????????")
				{
					$subResult = "????????????????";
				}
				break;
			}
			if($lastVideoId == $subResult)
			{
				continue;
			}
			break;
		}
		echo $subResult;
	}
	
	public function action_analyzeChannels()
	{
		$request = $this->request;
		$session = Session::instance();
		$servicePattern = new Service_Pattern();
		
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		if(Model_Share::sharedExist($sessionid) === false)
		{
			Model_Share::createShared($sessionid);
		}
		$sharedid = Model_Share::getShareId($sessionid);
		
		$queue = new Service_Queue(512, $sharedid, $sharedid);
		$queue->initResAnalyze();
		
		$stopanalyze = $sessionid . "stop";
		if(Model_Share::sharedExist($stopanalyze) === false)
		{
			Model_Share::createShared($stopanalyze);
		}
		$sharedid = Model_Share::getShareId($stopanalyze);
		
		$stopqueue = new Service_Queue(512, $sharedid, $sharedid);
		$stopqueue->initStop();
		
		$pauseanalyze = $sessionid . "pause";
		if(Model_Share::sharedExist($pauseanalyze) === false)
		{
			Model_Share::createShared($pauseanalyze);
		}
		$sharedid = Model_Share::getShareId($pauseanalyze);
		
		$pausequeue = new Service_Queue(512, $sharedid, $sharedid);
		$pausequeue->initStop();
		
		$channelsnalyze = $sessionid . "channels";
		if(Model_Share::sharedExist($channelsnalyze) === false)
		{
			Model_Share::createShared($channelsnalyze);
		}
		$sharedid = Model_Share::getShareId($channelsnalyze);
		
		$channelIds = $body['channelIds'];
		$channelsStr = implode(",", $channelIds);
		
		$channelsqueue = new Service_Queue(strlen($channelsStr)+15, $sharedid, $sharedid);
		$channelsqueue->initPause();
		$patternId = $body['patternId'];
		
		
		foreach($channelIds as $channelId)
		{
			$result = Service_Pattern::analizeChannel($request, $session, $channelId, $patternId, $queue, $stopqueue, $pausequeue, $channelsqueue);
			if($result['return_type'] !== 0)
			{
				echo json_encode($result);
				return;
			}
		}
		echo json_encode(array('return_type' => 0, 'result' => "true"));
	}
	
	public function action_continueAnalyze()
	{
		$request = $this->request;
		$session = Session::instance();
		$servicePattern = new Service_Pattern();
		
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		if(Model_Share::sharedExist($sessionid) === false)
		{
			Model_Share::createShared($sessionid);
		}
		$sharedid = Model_Share::getShareId($sessionid);
		
		$queue = new Service_Queue(512, $sharedid, $sharedid);
		$queue->initResAnalyze();
		
		$stopanalyze = $sessionid . "stop";
		if(Model_Share::sharedExist($stopanalyze) === false)
		{
			Model_Share::createShared($stopanalyze);
		}
		$sharedid = Model_Share::getShareId($stopanalyze);
		
		$stopqueue = new Service_Queue(512, $sharedid, $sharedid);
		$stopqueue->initStop();
		
		$pauseanalyze = $sessionid . "pause";
		if(Model_Share::sharedExist($pauseanalyze) === false)
		{
			Model_Share::createShared($pauseanalyze);
		}
		$sharedid = Model_Share::getShareId($pauseanalyze);
		
		$pausequeue = new Service_Queue(512, $sharedid, $sharedid);
		$pausequeue->initStop();
		
		$channelsnalyze = $sessionid . "channels";
		if(Model_Share::sharedExist($channelsnalyze) === false)
		{
			Model_Share::createShared($channelsnalyze);
		}
		$sharedid = Model_Share::getShareId($channelsnalyze);
		
		$channelsqueue = new Service_Queue(5, $sharedid, $sharedid);
		
		$length = $channelsqueue->pop(5);
		
		$length = $length + 0;
		
		$channelsqueue = new Service_Queue($length + 15, $sharedid, $sharedid);
		
		$str = $channelsqueue->pop($length + 15);
		
		$patternId = substr($str, 5, 10);
		$patternId = $patternId + 0;
		
		$channelIds = substr($str, 15);
		$channelIds = explode(",", $channelIds);
		
		foreach($channelIds as $channelId)
		{
			$result = Service_Pattern::analizeChannel($request, $session, $channelId, $patternId, $queue, $stopqueue, $pausequeue, $channelsqueue);
			if($result['return_type'] !== 0)
			{
				echo json_encode($result);
				return;
			}
		}
		echo json_encode(array('return_type' => 0, 'result' => "true"));
	}
	
	public function action_stopAnalyze()
	{
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		$stopanalyze = $sessionid . "stop";
		if(Model_Share::sharedExist($stopanalyze) === false)
		{
			Model_Share::createShared($stopanalyze);
		}
		$sharedid = Model_Share::getShareId($stopanalyze);
		
		$queue = new Service_Queue(512, $sharedid, $sharedid);
		$queue->push("astop");
	}
	
	public function action_pauseAnalyze()
	{
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		$pauseanalyze = $sessionid . "pause";
		if(Model_Share::sharedExist($pauseanalyze) === false)
		{
			Model_Share::createShared($pauseanalyze);
		}
		$sharedid = Model_Share::getShareId($pauseanalyze);
		
		$queue = new Service_Queue(512, $sharedid, $sharedid);
		$queue->push("pause");
	}
	
	public function action_analyzeChannel()
	{
	    $request = $this->request;
	    $session = Session::instance();
	    $servicePattern = new Service_Pattern();
	    
	    $queue = new Service_Queue(512, 2, 2);
	    $queue->initResAnalyze();
	    
	    $body = $this->request->post();
	    $channelId = $body['channelId'];
	    $patternId = $body['patternId'];
	    $result = Service_Pattern::analizeChannel($request, $session, $channelId, $patternId, $queue);
	    echo json_encode($result);
	    
	}
	
	public function action_deletePattern()
	{
	    //Service_Pattern::deletePatternByName('Направление');
		$body = $this->request->post();
		$patternId = $body['patternId'];
		echo Service_Pattern::deletePatternById($patternId);
	}
	
	public function action_getPatternById()
	{
	    Service_Pattern::getPatternById(13);
	}
	
	public function action_getAllPatterns()
	{
		echo json_encode(Pattern::getAllPatterns());
	}
	
	public function action_authorize()
	{
		$request = $this->request;
		$session = Session::instance();
		$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
				'oMbF7Zj1K9cCVXw3ZVGFN5z-');
		$result = $apiServicre->authorize("authorize", $request, $session);
		if($result === 1)
		{
			$this->response->body(View::factory('Analyze'));
		}
		else if($result === 0)
		{
			echo true;
		}
		else 
		{
			echo $result;
		}
		
	}

} // End Welcome
