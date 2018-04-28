<?php defined('SYSPATH') or die('No direct script access.');
//

class Controller_Welcome extends Controller {

	
	public function action_index()
	{
		$this->response->body('hello, world!');
	}

	public function action_test()
	{
		$date = new DateTime();
		$data = array(
				'sessionid' => Session::instance()->id() . $date->getTimestamp()
		);
		$this->response->body(View::factory('Prepare', $data));
	}
	
	public function action_analyze()
	{
		$date = new DateTime();
		$data = array(
				'sessionid' => Session::instance()->id() . $date->getTimestamp()
		);
		$this->response->body(View::factory('Analyze', $data));
	}
	
	public function action_monitor()
	{
		$date = new DateTime();
		$data = array(
				'sessionid' => Session::instance()->id() . $date->getTimestamp()
		);
		$this->response->body(View::factory('Monitor', $data));
	}
	
	public function action_createPattern()
	{
		$body = $this->request->post();
		$patternName = $body['patternName'];
		$videoIds = $body['videoIds'];
		$videoIds = explode(",",$videoIds);
	    $servicePattern = new Service_Pattern();
	    $sessionid = $body['sessionid'];
	    Model_CreateResult::init($sessionid);
	    try
	    {
	    	$pattern = $servicePattern->createPattern($patternName, $videoIds, $sessionid);
	    	Model_CreateResult::init($sessionid);
	    	echo json_encode($pattern);
	    }
	    catch(Exception $e)
	    {
	    	Model_CreateResult::init($sessionid);
	    	echo json_encode(array('type'=>0, 'result'=>false));
	    }
	}
	
	public function action_getSubResult()
	{
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		echo Model_CreateResult::popResult($sessionid);
	}
	
	public function action_getSubResultResAnalyze()
	{
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		echo json_encode(Model_Result::popAllResults($sessionid));
	}
	
	public function action_analyzeChannels()
	{
		$request = $this->request;
		$session = Session::instance();
		
		$body = $this->request->post();
		
		$sessionid = $body['sessionid'];
		$channelIds = $body['channelIds'];
		$patternId = $body['patternId'];
		
		Model_StopAnalyze::init($sessionid);
		Model_PauseAnalyze::init($sessionid);
		
		
		foreach($channelIds as $channelId)
		{
			$result = Service_Pattern::analizeChannel($request, $session, $channelId, $patternId, $sessionid);
			if($result['return_type'] !== 0)
			{
				echo json_encode($result);
				return;
			}
		}
		echo json_encode(array('return_type' => 0, 'result' => Model_Result::popAllResults($sessionid)));
	}
	
	public function action_continueAnalyze()
	{
		$request = $this->request;
		$session = Session::instance();
		$servicePattern = new Service_Pattern();
		
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		$saved = Model_SaveResult::popAllResults($sessionid);
		
		if(empty($saved))
		{
			echo json_encode(array('return_type' => 0, 'result' => "false"));
			return;
		}
		$videoIds = array();
		foreach($saved as $res)
		{
			array_push($videoIds, $res['videoid']);
		}
		$patternId = $saved[0]['patternid'];
		Model_PauseAnalyze::init($sessionid);
		Model_StopAnalyze::init($sessionid);
		$result = Service_Pattern::analizeVideos($videoIds, $patternId, $sessionid);
		
		if($result['return_type'] !== 0)
		{
			echo json_encode($result);
			return;
		}
		echo json_encode(array('return_type' => 0, 'result' => Model_Result::popAllResults($sessionid)));
	}
	
	public function action_stopAnalyze()
	{
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		Model_StopAnalyze::stop($sessionid);
	}
	
	public function action_pauseAnalyze()
	{
		$body = $this->request->post();
		$sessionid = $body['sessionid'];
		
		Model_PauseAnalyze::pause($sessionid);
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
	public function action_checkLastVideos()
	{
		$request = $this->request;
		$session = Session::instance();
		
		$body = $this->request->post();
		
		$sessionid = $body['sessionid'];
		$channelIds = $body['channelIds'];
		$patternId = $body['patternId'];
		$lastVideoId = $body['lastVideoId'];
		
		Model_StopAnalyze::init($sessionid);
		Model_PauseAnalyze::init($sessionid);
		
		echo json_encode(Service_Pattern::checkLastVideos($request, $session, $channelIds, $patternId, $sessionid, $lastVideoId));
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
			header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . "/analyze");
			exit();
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
	
	public function action_authorizeMonitor()
	{
		$request = $this->request;
		$session = Session::instance();
		$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
				'oMbF7Zj1K9cCVXw3ZVGFN5z-');
		$result = $apiServicre->authorize("authorizeMonitor", $request, $session);
		if($result === 1)
		{
			header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . "/monitor");
			exit();
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
