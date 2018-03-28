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
    public function authorize($request = NULL, $session = NULL)
    {
        $this->client = new Google_Client();
        $this->client->setClientId($this->OAUTH2_CLIENT_ID);
        $this->client->setClientSecret($this->OAUTH2_CLIENT_SECRET);
        $this->client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            FILTER_SANITIZE_URL);
        $this->client->setRedirectUri($redirect);
        
        // Define an object that will be used to make all API requests.
        $this->youtube = new Google_Service_YouTube($this->client);
        
        // Check if an auth token exists for the required scopes
        $this->tokenSessionKey = 'token-' . $this->client->prepareScopes();
        if ($request->query('code') !== NULL) {
            if (strval($session->get('state')) !== strval($request->query('state'))) {
                die('The session state did not match.');
            }
            
            $this->client->authenticate($request->query('code'));
            $session->set($this->tokenSessionKey, $this->client->getAccessToken());
            header('Location: ' . $redirect);
        }
        
        if ($session->get($this->tokenSessionKey) !== NULL) {
            $this->client->setAccessToken($session->get($this->tokenSessionKey));
        }
    }
    
    public function getChannelsVideo($session)
    {
        // Check to ensure that the access token was successfully acquired.
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
                $response = $this->youtube->search->listSearch('snippet', array('maxResults' => 10, 'channelId' => 'UChFcVtxhHg7uED8osbCsYhw'));
                $videoId = $response->items[0]->id->videoId;
                //$subtitle = getSubtitleByVideId($videoId);
                //$subtitle = $subtitle;
                $htmlBody = $videoId;
                
            } catch (Google_Service_Exception $e) {
                $htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }
            
            $session->set($this->tokenSessionKey, $this->client->getAccessToken());
        }
        else 
        {
            // If the user hasn't authorized the app, initiate the OAuth flow
            $state = mt_rand();
            $this->client->setState($state);
            $session->set('state', $state);
            
            $authUrl = $this->client->createAuthUrl();
            $htmlBody = '<h3>Authorization Required</h3><p>You need to <a href=' . $authUrl . '>authorize access</a> before proceeding.<p>';
        }
        return $htmlBody;
    }
}