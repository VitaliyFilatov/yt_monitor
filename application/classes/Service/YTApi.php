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
    
    public function __construct($clientId, $clientSecret)
    {
        $this->OAUTH2_CLIENT_ID = $clientId;
        $this->OAUTH2_CLIENT_SECRET = $clientSecret;
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
        	//$this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
        	return $result;
        }
        else
        {
        	$state = mt_rand();
        	$this->client->setState($state);
        	$session->set('state', $state);
        	
        	$authUrl = $this->client->createAuthUrl();
        	$htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
        	return $htmlBody;
        }
    }
    
    public function getChannelsVideo($session, $channelId)
    {
        // Check to ensure that the access token was successfully acquired.
        if ($this->client->getAccessToken()) {
            try {
            	$this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
                /*
                 * Before channel shelves will appear on your channel's web page, browse
                 * view needs to be enabled. If you know that your channel already has
                 * it enabled, or if you want to add a number of sections before enabling it,
                 * you can skip the call to enable_browse_view().
                 */
                
                // Call the YouTube Data API's channels.list method to retrieve your channel.
                //$listResponse = $youtube->channels->listChannels('brandingSettings', array('mine' => true));
                //$response = $youtube->channels->listChannels('invideoPromotion', array('id' => 'UC6zOnSAtJzz166Y5B7tImNA'));
                //$response = $youtube->videos->listVideos('contentDetails', array('id' => 'MHjrim3TdVE'));
                $videoIds = array();
                $pageToken = '';
               
                do
                {
                    $response = $this->youtube->search->listSearch('snippet', array('maxResults' => 50,
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
                
                
                
                //$response->items[0]->id->videoId
                
                //$subtitle = getSubtitleByVideId($videoId);
                //$subtitle = $subtitle;
                $htmlBody = array('return_type' => 0, 'result' => $videoIds);
                
            } catch (Google_Service_Exception $e) {
                $htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
                Kohana::$log->add(Log::DEBUG, $e->getMessage());
                if($e->getCode() == 401 || $e->getCode() == 403)
                {
                	$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
                }
                else 
                {
                	$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
                }
            } catch (Google_Exception $e) {
                $htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
                Kohana::$log->add(Log::DEBUG, $e->getMessage());
                $htmlBody = array('return_type' => 2, 'result' => $htmlBody);
            }
            
            $session->set($this->tokenSessionKey, $this->client->getAccessToken());
        }
        if($htmlBody['return_type'] === 1) 
        {
            // If the user hasn't authorized the app, initiate the OAuth flow
            $state = mt_rand();
            $this->client->setState($state);
            $session->set('state', $state);
            
            $authUrl = $this->client->createAuthUrl();
            $htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
            $htmlBody = array('return_type' => 1, 'result' => $htmlBody);
        }
        return $htmlBody;
    }
    
    public function getVideoComment($session, $videoId)
    {
    	if ($this->client->getAccessToken()) {
    		try {
    			
    			/*
    			 * Before channel shelves will appear on your channel's web page, browse
    			 * view needs to be enabled. If you know that your channel already has
    			 * it enabled, or if you want to add a number of sections before enabling it,
    			 * you can skip the call to enable_browse_view().
    			 */
    			
    			// Call the YouTube Data API's channels.list method to retrieve your channel.
    			//$listResponse = $youtube->channels->listChannels('brandingSettings', array('mine' => true));
    			//$response = $youtube->channels->listChannels('invideoPromotion', array('id' => 'UC6zOnSAtJzz166Y5B7tImNA'));
    			//$response = $youtube->videos->listVideos('contentDetails', array('id' => 'MHjrim3TdVE'));
    			$сomments = array();
    			$pageToken = '';
    			$this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
    			
    			do
    			{
    				$response = $this->youtube->commentThreads->listCommentThreads('snippet,replies', array('maxResults' => 100,
    						'videoId' => $videoId,
    						'pageToken' => $pageToken
    				));
    				
    				foreach($response->items as $item)
    				{
    					array_push($сomments, $item->snippet->topLevelComment->snippet->textDisplay);
    				}
    				
    				if(isset($response->nextPageToken))
    				{
    					$pageToken = $response->nextPageToken;
    				}
    			}
    			while(isset($response->nextPageToken));
    			
    			
    			
    			//$response->items[0]->id->videoId
    			
    			//$subtitle = getSubtitleByVideId($videoId);
    			//$subtitle = $subtitle;
    			$htmlBody = array('return_type' => 0, 'result' => $сomments);
    			
    		} catch (Google_Service_Exception $e) {
    			$htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			if($e->getCode() == 401 || $e->getCode() == 403)
    			{
    				$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    			}
    			else
    			{
    				$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    			}
    		} catch (Google_Exception $e) {
    			$htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    		}
    		
    		$session->set($this->tokenSessionKey, $this->client->getAccessToken());
    	}
    	if($htmlBody['return_type'] === 1)
    	{
    		// If the user hasn't authorized the app, initiate the OAuth flow
    		$state = mt_rand();
    		$this->client->setState($state);
    		$session->set('state', $state);
    		
    		$authUrl = $this->client->createAuthUrl();
    		$htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
    		$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    	}
    	return $htmlBody;
    }

    public function getVideoStatistics($session, $videoId)
    {
    	if ($this->client->getAccessToken()) {
    		try {
    			
    			$this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
    			$response = $this->youtube->videos->listVideos('statistics', array('id' => $videoId));
    			
    			
    			
    			//$response->items[0]->id->videoId
    			
    			//$subtitle = getSubtitleByVideId($videoId);
    			//$subtitle = $subtitle;
    			$htmlBody = array('return_type' => 0,
    					'result' => $response->items[0]->statistics);
    			
    		} catch (Google_Service_Exception $e) {
    			$htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			if($e->getCode() == 401 || $e->getCode() == 403)
    			{
    				$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    			}
    			else
    			{
    				$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    			}
    		} catch (Google_Exception $e) {
    			$htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    		}
    		
    		$session->set($this->tokenSessionKey, $this->client->getAccessToken());
    	}
    	if($htmlBody['return_type'] === 1)
    	{
    		// If the user hasn't authorized the app, initiate the OAuth flow
    		$state = mt_rand();
    		$this->client->setState($state);
    		$session->set('state', $state);
    		
    		$authUrl = $this->client->createAuthUrl();
    		$htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
    		$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    	}
    	return $htmlBody;
    }
    
    
    public function getChannelStatistics($session, $channelId)
    {
    	if ($this->client->getAccessToken()) {
    		try {
    			
    			$this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
    			$response = $this->youtube->channels->listChannels('statistics', array('id' => $channelId));
    			
    			
    			
    			//$response->items[0]->id->videoId
    			
    			//$subtitle = getSubtitleByVideId($videoId);
    			//$subtitle = $subtitle;
    			$htmlBody = array('return_type' => 0,
    					'result' => $response->items[0]->statistics);
    			
    		} catch (Google_Service_Exception $e) {
    			$htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			if($e->getCode() == 401 || $e->getCode() == 403)
    			{
    				$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    			}
    			else
    			{
    				$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    			}
    		} catch (Google_Exception $e) {
    			$htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    		}
    		
    		$session->set($this->tokenSessionKey, $this->client->getAccessToken());
    	}
    	if($htmlBody['return_type'] === 1)
    	{
    		// If the user hasn't authorized the app, initiate the OAuth flow
    		$state = mt_rand();
    		$this->client->setState($state);
    		$session->set('state', $state);
    		
    		$authUrl = $this->client->createAuthUrl();
    		$htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
    		$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    	}
    	return $htmlBody;
    }
    
    public function getLastChannelsVideo($session, $channelId)
    {
    	//Check to ensure that the access token was successfully acquired.
    	if ($this->client->getAccessToken()) {
    		try {
    			$this->client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);
    			/*
    			 * Before channel shelves will appear on your channel's web page, browse
    			 * view needs to be enabled. If you know that your channel already has
    			 * it enabled, or if you want to add a number of sections before enabling it,
    			 * you can skip the call to enable_browse_view().
    			 */
    			
    			// Call the YouTube Data API's channels.list method to retrieve your channel.
    			//$listResponse = $youtube->channels->listChannels('brandingSettings', array('mine' => true));
    			//$response = $youtube->channels->listChannels('invideoPromotion', array('id' => 'UC6zOnSAtJzz166Y5B7tImNA'));
    			//$response = $youtube->videos->listVideos('contentDetails', array('id' => 'MHjrim3TdVE'));
    			$videoIds = array();
    			$pageToken = '';
    			
    			$response = $this->youtube->search->listSearch('snippet', array('maxResults' => 1,
    					'channelId' => $channelId,
    					'order' => 'date'
    			));
    			
    			foreach($response->items as $item)
    			{
    				array_push($videoIds, $item->id->videoId);
    			}
    			
    			
    			
    			//$response->items[0]->id->videoId
    			
    			//$subtitle = getSubtitleByVideId($videoId);
    			//$subtitle = $subtitle;
    			$htmlBody = array('return_type' => 0, 'result' => $videoIds);
    			
    		} catch (Google_Service_Exception $e) {
    			$htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			if($e->getCode() == 401 || $e->getCode() == 403)
    			{
    				$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    			}
    			else
    			{
    				$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    			}
    		} catch (Google_Exception $e) {
    			$htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
    					htmlspecialchars($e->getMessage()));
    			Kohana::$log->add(Log::DEBUG, $e->getMessage());
    			$htmlBody = array('return_type' => 2, 'result' => $htmlBody);
    		}
    		
    		$session->set($this->tokenSessionKey, $this->client->getAccessToken());
    	}
    	if($htmlBody['return_type'] === 1)
    	{
    		// If the user hasn't authorized the app, initiate the OAuth flow
    		$state = mt_rand();
    		$this->client->setState($state);
    		$session->set('state', $state);
    		
    		$authUrl = $this->client->createAuthUrl();
    		$htmlBody = '<a id="authLink" href=' . $authUrl . '></a>';
    		$htmlBody = array('return_type' => 1, 'result' => $htmlBody);
    	}
    	return $htmlBody;
    }
}