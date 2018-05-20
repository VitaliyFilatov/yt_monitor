<?php
defined('SYSPATH') or die('No direct script access.');

require_once Kohana::find_file('vendor', 'google-api-php-client-2.2.1/vendor/autoload', 'php');

class Service_YTApi
{
    
    protected $OAUTH2_CLIENT_ID;
    protected $OAUTH2_CLIENT_SECRET;
    
    protected $client;
    protected $youtube;
    protected $tokenSessionKey;
    
    private static function execGetChannelsVideo($channelId, $client)
    {
    	$videoIds = array();
    	$pageToken = '';
    	
    	do
    	{
    		$response = $client->youtube->search->listSearch('snippet', array('maxResults' => 50,
    				'channelId' => $channelId,
    				'pageToken' => $pageToken
    		));
    		
    		foreach($response->items as $item)
    		{
    			array_push($videoIds, $item->id->videoId);
    		}
    		
    		if(isset($response->nextPageToken))
    		{
    			$pageToken = $response->nextPageToken;
    		}
    	}
    	while(isset($response->nextPageToken));
    	
    	return new Entity_ReturnResult(0, $videoIds);
    }
    
    
    private static function execGetVideoComment($videoId, $client, $maxCount = -1)
    {
    	$сomments = array();
    	$pageToken = '';
    	
    	do
    	{
    		$response = $client->youtube->commentThreads->listCommentThreads('snippet,replies', array('maxResults' => 100,
    				'videoId' => $videoId,
    				'pageToken' => $pageToken
    		));
    		
    		foreach($response->items as $item)
    		{
    			array_push($сomments, $item->snippet->topLevelComment->snippet->textDisplay);
    		}
    		if($maxCount > 0)
    		{
    			if($maxCount < count($сomments))
    			{
    				break;
    			}
    		}
    		
    		if(isset($response->nextPageToken))
    		{
    			$pageToken = $response->nextPageToken;
    		}
    	}
    	while(isset($response->nextPageToken));
    	
    	return new Entity_ReturnResult(0, $сomments);
    }
    
    private static function execGetVideoStatistics($videoId, $client)
    {
    	$response = $client->youtube->videos->listVideos('statistics', array('id' => $videoId));
    	
    	return new Entity_ReturnResult(0, $response->items[0]->statistics);
    }
    
    private static function execGetChannelStatistics($channelId, $client)
    {
    	$response = $client->youtube->channels->listChannels('statistics', array('id' => $channelId));
    	
    	return new Entity_ReturnResult(0, $response->items[0]->statistics);
    }
    
    private static function execGetLastChannelsVideo($channelId, $client)
    {
    	$videoIds = array();
    	$pageToken = '';
    	
    	$response = $client->youtube->search->listSearch('snippet', array('maxResults' => 1,
    			'channelId' => $channelId,
    			'order' => 'date'
    	));
    	
    	foreach($response->items as $item)
    	{
    		array_push($videoIds, $item->id->videoId);
    	}
    	
    	return new Entity_ReturnResult(0, $videoIds);
    }
    
    
    public function __construct($clientId, $clientSecret)
    {
        $this->OAUTH2_CLIENT_ID = $clientId;
        $this->OAUTH2_CLIENT_SECRET = $clientSecret;
    }
    
    private function getAuthLink($session)
    {
    	$state = mt_rand();
    	$this->client->setState($state);
    	$session->set('state', $state);
    	
    	$authUrl = $this->client->createAuthUrl();
    	$htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
    	return $htmlBody;
    }
   
    /**
     * 
     * @param Request $request
     * @param Session_Native $session
     */
    public function authorize($path, $request = NULL, $session = NULL)
    {
        $this->client = new Google_Client();
        $this->client->setClientId($this->OAUTH2_CLIENT_ID);
        $this->client->setClientSecret($this->OAUTH2_CLIENT_SECRET);
        $this->client->setScopes('https://www.googleapis.com/auth/youtube');
        $this->client->setAccessType('offline');        // offline access
        $this->client->setIncludeGrantedScopes(true);   // incremental auth
//          $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
//              FILTER_SANITIZE_URL);
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . "/" . $path);
        $this->client->setRedirectUri($redirect);
        
        // Define an object that will be used to make all API requests.
        $this->youtube = new Google_Service_YouTube($this->client);
        $this->client->addScope(Google_Service_YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT);
        $this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
        // Check if an auth token exists for the required scopes
        $this->tokenSessionKey = 'token-' . $this->client->prepareScopes();
        $result = 0;
        if ($request->query('code') !== NULL) {
            if (strval($session->get('state')) !== strval($request->query('state'))) {
                die('The session state did not match.');
            }
            
            $this->client->authenticate($request->query('code'));
            $session->set($this->tokenSessionKey, $this->client->getAccessToken());
            $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . "/" . $path);
            header('Location: ' . $redirect);
            $result = 1;
        }
        
        if ($session->get($this->tokenSessionKey) !== NULL) {
            $this->client->setAccessToken($session->get($this->tokenSessionKey));
        }
        
        
        if ($this->client->getAccessToken())
        {
        	$session->set($this->tokenSessionKey, $this->client->getAccessToken());
        	return $result;
        }
        else
        {
        	return $this->getAuthLink($session);
        }
    }
    
    private function useService($session, $exec, $arg1, $arg2, $arg3 = null)
    {
    	if ($this->client->getAccessToken()) {
    		try {
    			
    			$videoIds = array();
    			$pageToken = '';
    			
    			$htmlBody = call_user_func($exec, $arg1, $arg2, $arg3);
    			
    		} catch (Google_Service_Exception $e) {
    			$htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			if($e->getCode() == 401 || $e->getCode() == 403)
    			{
    				$htmlBody = new Entity_ReturnResult(1, $htmlBody);
    			}
    			else
    			{
    				$htmlBody = new Entity_ReturnResult(2, $htmlBody);
    			}
    		} catch (Google_Exception $e) {
    			$htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			$htmlBody = new Entity_ReturnResult(1, $htmlBody);
    		}
    		
    		//$session->set($this->tokenSessionKey, $this->client->getAccessToken());
    	}
    	if($htmlBody->return_type === 1)
    	{
    		$htmlBody = new Entity_ReturnResult(1, $this->getAuthLink($session));
    	}
    	return $htmlBody;
    }
    
    public function getChannelsVideo($session, $channelId)
    {
    	return $this->useService($session, "Service_YTApi::execGetChannelsVideo", $channelId, $this);
    }
    
    public function getVideoComment($session, $videoId, $maxCount = -1)
    {
    	return $this->useService($session, "Service_YTApi::execGetVideoComment", $videoId, $this, $maxCount);
    }

    public function getVideoStatistics($session, $videoId)
    {
    	return $this->useService($session, "Service_YTApi::execGetVideoStatistics", $videoId, $this);
    }
    
    
    public function getChannelStatistics($session, $channelId)
    {
    	return $this->useService($session, "Service_YTApi::execGetChannelStatistics", $channelId, $this);
    }
    
    public function getLastChannelsVideo($session, $channelId)
    {
    	return $this->useService($session, "Service_YTApi::execGetLastChannelsVideo", $channelId, $this);
    }
}