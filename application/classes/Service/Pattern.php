<?php
defined('SYSPATH') or die('No direct script access.');

class Service_Pattern
{	
	private static $sentThreshold = 0.77;
	
	private static $max_timeout = 10;
    
    
    static function getStatistics($arrayStats)
    {
        $result = array();
        $wordsCount = 0;
        foreach($arrayStats as $wordsstat)
        {
            foreach($wordsstat as $key=>$value)
            {
                if(!array_key_exists($key, $result))
                {
                    $result[$key] = 0;
                }
                $result[$key] += $value;
                $wordsCount += $value;
            }
        }
        foreach($result as $key=>$value)
        {
            $result[$key] = $result[$key] / $wordsCount;
        }
        return $result;
    }
    
    
    //find words from $arr2 in $arr1 and calculate abs of difference
    //between parts of words in unput arrays
    //result this function is associative array: word=>difference of part
    function getDifferentInWordParts($arr1, $arr2)
    {
        $result = array();
        foreach($arr2 as $key=>$value)
        {
            if(array_key_exists($key, $arr1))
            {
                $result[$key] = abs($arr1[$key] - $value);
            }
            else
            {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    
    function getGeneralWords($filepath)
    {
        $file = file(trim($filepath));
        $result = array();
        $str;
        foreach($file as $recordstring)
        {
            $record = explode(' ', $recordstring);
            $result[$record[2]] = $record[1] / 1000000;
        }
        return $result;
    }
   
    
    public static function stopAllProcess($session)
    {
    	$sessionid = $session->id();
    	Model_StopAnalyze::stopAllSessionProcess($sessionid);
    }
    
    public static function getSimilarityWithPattern($pattern, $resCA)
    {
        $dif = 0;
        $maxDif = 0;
        $i = 0;
        $freq;
        foreach($pattern->words as $words)
        {
            if(!array_key_exists($words["word"], $resCA))
            {
                $freq = 0;
            }
            else
            {
                $freq = $resCA[$words["word"]];
            }
            if($freq < $words["frequency"])
            {
            	$dif += pow($freq - $words["frequency"], 2) * $words["frequency"] * $words["dif_frequency"];
            }
            $maxDif += pow($words["frequency"],3) * $words["dif_frequency"];
            $i++;
        }
        
        
        return 1 - $dif / $maxDif;
    }

    public function createPattern($patternName, $videoIds, $sessionid)
    {
    	$genWords = $this->getGeneralWords(APPPATH . "files/lemma.num");
        $videoIdsWithSubtls = array();
        $wordsstat = array();
        $i=0;
        foreach($videoIds as $videoId)
        {
        	$i++;
            $subtitle = Service_SubtleService::getSubtitleByVideId($videoId);
            if($subtitle->return_type === 0)
            {
            	return new Entity_ReturnResult(0, $videoId);
            }
            $subtitle = $subtitle->result;
            array_push($wordsstat, Service_ContentAnalyse::parseContentAnalize(Service_ContentAnalyse::getContentAnaliz($subtitle, $videoId)));
            array_push($videoIdsWithSubtls, $videoId);
            Model_CreateResult::addResult($sessionid, round($i/count($videoIds)*100));
            
        }
        $results = $this->getStatistics($wordsstat);
        $diff = $this->getDifferentInWordParts($genWords, $results);
        
        Service_Pattern::deletePatternByName($patternName);
        
        $pattern = ORM::factory('Pattern');
        $pattern->name = $patternName;
        $pattern->save();
        foreach($videoIdsWithSubtls as $videoId)
        {
            $video = ORM::factory('PatternVideo');
            $video->video_id = $videoId;
            $video->pattern_id = $pattern->id;
            $video->save();
        }
        
        foreach($results as $key => $value)
        {
            $words = ORM::factory('PatternWords');
            $words->word = $key;
            $words->frequency = $value;
            $words->dif_frequency = $diff[$key];
            $words->pattern_id = $pattern->id;
            $words->save();
        }
        
        $patternEntity = new Entity_Pattern($pattern->id, true);
        return new Entity_ReturnResult(1, $patternEntity);
    }
    
    public static function simByVideoId($videoId, $sims)
    {
    	foreach($sims as $sim)
    	{
    		if($sim['videoId'] == $videoId)
    		{
    			return $sim['sim'];
    		}
    	}
    	return -1;
    }
    
    public static function countLessThen($videoIds, $sims, $value)
    {
    	$count = 0;
    	foreach($videoIds as $videoId)
    	{
    		$sim = Service_Pattern::simByVideoId($videoId, $sims);
    		if($sim != -1)
    		{
    			if($sim < $value)
    			{
    				$count++;
    			}
    		}
    	}
    	return $count;
    }
    
    public static function countMoreThenOrEqual($videoIds, $sims, $value)
    {
    	$count = 0;
    	foreach($videoIds as $videoId)
    	{
    		$sim = Service_Pattern::simByVideoId($videoId, $sims);
    		if($sim != -1)
    		{
    			if($sim >= $value)
    			{
    				$count++;
    			}
    		}
    	}
    	return $count;
    }
    
    public function calcThreshold($patternid, $destrVideoIds, $nondestrVideoIds, $sessionid)
    {
    	$videoIds = array_merge($destrVideoIds, $nondestrVideoIds);
    	$sims = Service_Pattern::analizeVideosForThreshold($videoIds, $patternid, $sessionid);
    	$sims = $sims->result;
    	$values = array();
    	foreach($sims as $sim)
    	{
    		array_push($values, $sim['sim']);
    	}
    	rsort($values);
    	$errors = count($videoIds);
    	$threshold = 1;
    	foreach($values as $value)
    	{
    		$current = 0;
    		$current += Service_Pattern::countLessThen($destrVideoIds, $sims, $value);
    		$current += Service_Pattern::countMoreThenOrEqual($nondestrVideoIds, $sims, $value);
    		if($current > $errors)
    		{
    			Model_Pattern::setThreshold($patternid, $threshold);
    			return $threshold;
    		}
    		else
    		{
    			$errors = $current;
    			$threshold = $value;
    		}
    	}
    	return -1;
    }
    
    public static function deletePatternByName($name)
    {
        Model_Pattern::deleteByName($name);
    }
    
    public static function deletePatternById($id)
    {
    	return Model_Pattern::deletePatternById($id);
    }
    
    public static function analizeVideo($videoId, $pattern)
    {
        $subtitle = Service_SubtleService::getSubtitleByVideId($videoId);
        if($subtitle->return_type == 0)
        {
        	return "nosub";
        }
        else 
        {
        	$subtitle = $subtitle->result;
        	$resCA = Service_Pattern::getStatistics(array(Service_ContentAnalyse::parseContentAnalize(Service_ContentAnalyse::getContentAnaliz($subtitle, $videoId))));
        	return Service_Pattern::getSimilarityWithPattern($pattern, $resCA);
        }
    }
    
    
    public static function getInfo($request, $session, $videoId, $reirect, $channelId, $sessionid)
    {
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	try
    	{
    		$apiServicre->authorize($reirect, $request, $session);
    		$videoStatistics = $apiServicre->getVideoStatistics($session, $videoId);
    		if($videoStatistics->return_type !== 0)
    		{
    			return $videoStatistics;
    		}
    		$videoStatistics = $videoStatistics->result;
    		$channelStatistics = $apiServicre->getChannelStatistics($session, $channelId);
    		if($channelStatistics->return_type !== 0)
    		{
    			return $channelStatistics;
    		}
    		$channelStatistics = $channelStatistics->result;
    	}
    	catch(Exception $e)
    	{
    		return new Entity_ReturnResult(2, $e->getMessage());
    	}
    	$result = Service_Pattern::sentimentComments($request, $session, $videoId, $sessionid);
    	if($result->return_type == 0)
    	{
    		$sumpos = 0;
    		$sumneg = 0;
    		foreach($result->result as $sentiment)
    		{
    			if($sentiment == 1)
    			{
    				$sumpos++;
    			}
    			else if($sentiment == -1)
    			{
    				$sumneg++;
    			}
    		}
    	}
    	else
    	{
    		return $result;
    	}
    	$videoInfo = new Entity_VideoInfo();
    	$videoInfo->like_count = $videoStatistics->likeCount;
    	$videoInfo->dislike_count = $videoStatistics->dislikeCount;
    	$videoInfo->positive_count = $sumpos;
    	$videoInfo->negative_count = $sumneg;
    	$videoInfo->view_count = $videoStatistics->viewCount;
    	$videoInfo->followers_count = $channelStatistics->subscriberCount;
    	return new Entity_ReturnResult(0, $videoInfo);
    }
    
    public static function isStop($sessionid, $previoustimestamp = null)
    {
    	$lasttimestamp = Model_LastGetResultAnalyze::getLastTimestamp($sessionid);
    	$currenttimestamp = new DateTime();
    	$currenttimestamp = $currenttimestamp->getTimestamp();
    	if($lasttimestamp !== null)
    	{
    		if($currenttimestamp - $lasttimestamp>= Service_Pattern::$max_timeout)
    		{
    			if(!Model_PauseAnalyze::isPause($sessionid))
    			{
    				Model_Result::popAllResults($sessionid);//clear result table
    				return new Entity_ReturnResult(3, $sessionid);
    			}
    		}
    	}
    	$previoustimestamp = $lasttimestamp;
    	if(Model_StopAnalyze::isStop($sessionid))
    	{
    		Model_Result::popAllResults($sessionid);//clear result table
    		return new Entity_ReturnResult(3, $sessionid);
    	}
    	return new Entity_ReturnResult(0, $previoustimestamp);
    }
    
    
    public static function analizeVideosWithChannels($videos, $patternId, $sessionid, $request, $session)
    {
    	$pattern = new Pattern($patternId);
    	$previoustimestamp = null;
    	foreach ($videos as $key=>$video)
    	{
    		$isStop = Service_Pattern::isStop($sessionid, $previoustimestamp);
    		if($isStop->return_type === 0)
    		{
    			$previoustimestamp = $isStop->result;
    		}
    		else if($isStop->return_type === 3)
    		{
    			return $isStop;
    		}
    		if(Model_PauseAnalyze::isPause($sessionid))
    		{
    			$sliced = array_slice($videos, $key);
    			Model_SaveResult::addResultWithChannels($sessionid, $sliced, $patternId);
    			return new Entity_ReturnResult(4, $sessionid);
    		}
    		if($video['videoId'] == null)
    		{
    			continue;
    		}
    		$sim = Service_Pattern::analizeVideo($video['videoId'], $pattern);
    		if($sim == "nosub")
    		{
    			$sim = -1;
    		}
    		if($pattern->threshold <= $sim)
    		{
    			$videoInfo = Service_Pattern::getInfo($request,
    					$session,
    					$video['videoId'],
    					"authorize",
    					$video['channelId'],
    					$sessionid);
    			if($videoInfo->return_type == 1)
    			{
    				return $videoInfo;
    			}
    			else if($videoInfo->return_type== 0)
    			{
    			    $videoInfo = $videoInfo->result;
    				Model_Result::addResultWithInfo($sessionid,
    						$video['videoId'],
    						$sim,
    						$videoInfo);
    			}
    			else {
    				Model_Result::addResult($sessionid, $video['videoId'], $sim);
    			}
    		}
    		else
    		{
    			Model_Result::addResult($sessionid, $video['videoId'], $sim);
    		}
    	}
    	return new Entity_ReturnResult(0, "true");
    }
    
    public static function analizeVideosForThreshold($videoIds, $patternId, $sessionid)
    {
    	$sims = array();
    	foreach ($videoIds as $key=>$videoId)
    	{
    		if($videoId == null)
    		{
    			continue;
    		}
    		$sim = Service_Pattern::analizeVideo($videoId, new Pattern($patternId));
    		if($sim == "nosub")
    		{
    			$sim = -1;
    		}
    		else 
    		{
    			array_push($sims, array('videoId'=>$videoId,'sim' => $sim));
    		}
    		Model_CreateResult::addResult($sessionid, round(($key + 1)/count($videoIds)*100));
    	}
    	return new Entity_ReturnResult(0, $sims);
    }
    
    public static function analizeMonitorVideos($videoIds, $patternId, $sessionid)
    {
    	foreach ($videoIds as $key=>$videoId)
    	{
    		if(Model_StopAnalyze::isStop($sessionid))
    		{
    			return new Entity_ReturnResult(0, "true");
    		}
    		if(Model_PauseAnalyze::isPause($sessionid))
    		{
    			$sliced = array_slice($videoIds, $key);
    			Model_SaveResult::addResult($sessionid, $sliced, $patternId);
    			return new Entity_ReturnResult(0, $sessionid);
    		}
    		if($videoId == null)
    		{
    			continue;
    		}
    		$sim = Service_Pattern::analizeVideo($videoId, new Pattern($patternId));
    		if($sim == "nosub")
    		{
    			$sim = -1;
    		}
    		Model_MonitorResult::addResult($sessionid, $videoId, $sim);
    	}
    	return new Entity_ReturnResult(0, "true");
    }
    
    public static function analizeChannels($request, $session, $channelIds, $patternId, $sessionid)
    {
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	try
    	{
    		$apiServicre->authorize("authorize", $request, $session);
    		$videos = array();
    		foreach($channelIds as $channelId)
    		{
    			$htmlBody = $apiServicre->getChannelsVideo($session, $channelId);
    			if($htmlBody->return_type !== 0)
    			{
    				return $htmlBody;
    			}
    			else 
    			{
    				foreach($htmlBody->result as $videoId)
    				{
    					array_push($videos, array('videoId'=>$videoId, 'channelId'=>$channelId));
    				}
    			}
    		}
    		return Service_Pattern::analizeVideosWithChannels($videos, $patternId, $sessionid, $request, $session);
    	}
    	catch(Exception $e)
    	{
    		return new Entity_ReturnResult(2, $e->getMessage());
    	}
    }
    

    
    public static function checkLastVideos($request, $session, $channelIds, $patternId, $sessionid, $lastVideoId)
    {
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	try
    	{
    		$apiServicre->authorize("authorizeMonitor", $request, $session);
    		$videoIds = array();
    		foreach($channelIds as $channelId)
    		{
    			$htmlBody = $apiServicre->getLastChannelsVideo($session, $channelId);
    			if($htmlBody->return_type !== 0)
    			{
    				return $htmlBody;
    			}
    			else 
    			{
    				if($htmlBody->result[0] == $lastVideoId)
    				{
    					return new Entity_ReturnResult(0, null);
    				}
    				$videoIds = array_merge($videoIds, $htmlBody->result);
    			}
    		}
    		if(!empty($videoIds))
    		{
    			Service_Pattern::analizeMonitorVideos($videoIds, $patternId, $sessionid);
    			return new Entity_ReturnResult(0, Model_MonitorResult::popAllResults($sessionid));
    		}
    		return new Entity_ReturnResult(0, null);
    	}
    	catch(Exception $e)
    	{
    		return new Entity_ReturnResult(2, $e->getMessage());
    	}
    }
    
    
    public static function sentimentComments($request, $session, $videoId, $sessionid)
    {
    	try
    	{
    		$htmlBody = Service_VideoComments::getVideoComments($request, $session, $videoId);
    		$sentiment = array();
    		foreach($htmlBody->result as $i=>$comment)
    		{
//     			if($i>=100)
//     			{
//     				break;
//     			}
    			$isStop = Service_Pattern::isStop($sessionid);
    			if($isStop->return_type === 3)
    			{
    				return $isStop;
    			}
    			$words = Service_ContentAnalyse::getContentAnalyzeIstio($comment);
    			$denominator = Model_PositiveWord::getCountUniqWords()+
    			Model_NegativeWord::getCountUniqWords()+
    			Model_NegativeWord::getSumFreq();
    			$sumneg = log10(0.5);
    			foreach($words as $word)
    			{
    				$sumneg += $word->freq * log10((Model_NegativeWord::getFreqByWord($word->word) + 1)/$denominator);
    			}
    			$denominator = Model_PositiveWord::getCountUniqWords()+
    			Model_NegativeWord::getCountUniqWords()+
    			Model_PositiveWord::getSumFreq();
    			$sumpos = log10(0.5);
    			foreach($words as $word)
    			{
    				$sumpos += $word->freq * log10((Model_PositiveWord::getFreqByWord($word->word) + 1)/$denominator);
    			}
    			if($sumpos < 0 && $sumneg < 0)
    			{
    				$min = min(array($sumpos,$sumneg));
    				$min = -$min;
    				$sumpos += $min;
    				$sumneg += $min;
    			}
    			$normpos = pow(10, $sumpos)/(pow(10, $sumpos)+pow(10, $sumneg));
    			$normneg = pow(10, $sumneg)/(pow(10, $sumpos)+pow(10, $sumneg));
    			if($normpos > Service_Pattern::$sentThreshold)
    			{
    				array_push($sentiment,1);
    			}
    			else if($normneg > Service_Pattern::$sentThreshold)
    			{
    				array_push($sentiment,-1);
    			}
    			else
    			{
    				array_push($sentiment,0);
    			}
    		}
    		return new Entity_ReturnResult(0, $sentiment);
    	}
    	catch(Exception $e)
    	{
    		return new Entity_ReturnResult(2, $e->getMessage());
    	}
    }
    
    
    //функция при работе приложения не используется
    //нужна для получения обучающей выборки навиного байесовского классификатора 
    public static function prepareBagOfWords($request, $session)
    {
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	
    	try
    	{
    		$apiServicre->authorize("authorize", $request, $session);
    		$htmlBody = $apiServicre->getVideoComment($session, "JkUGDu2HNv0");
    		$sentiment = array();
    		if($htmlBody->return_type !== 0)
    		{
    			return $htmlBody;
    		}
    		else
    		{
    			foreach($htmlBody->result as $i=>$comment)
    			{
    				if($i>=100)
    				{
    					break;
    				}
    				$words = Service_ContentAnalyse::getContentAnalyzeIstio($comment);
    				$denominator = Model_PositiveWord::getCountUniqWords()+
    				Model_NegativeWord::getCountUniqWords()+
    				Model_NegativeWord::getSumFreq();
    				$sumneg = log10(0.5);
    				foreach($words as $word)
    				{
    					$sumneg += $word->freq * log10((Model_NegativeWord::getFreqByWord($word->word) + 1)/$denominator);
    				}
    				$denominator = Model_PositiveWord::getCountUniqWords()+
    				Model_NegativeWord::getCountUniqWords()+
    				Model_PositiveWord::getSumFreq();
    				$sumpos = log10(0.5);
    				foreach($words as $word)
    				{
    					$sumpos += $word->freq * log10((Model_PositiveWord::getFreqByWord($word->word) + 1)/$denominator);
    				}
    				if($sumpos > $sumneg)
    				{
    					array_push($sentiment,array('comment'=>$comment, 'sentiment'=>"позитивная тональность"));
    				}
    				else 
    				{
    					array_push($sentiment,array('comment'=>$comment, 'sentiment'=>"негативная тональность"));
    				}
    			}
    		}
    		return new Entity_ReturnResult(0, null);
    	}
    	catch(Exception $e)
    	{
    		return new Entity_ReturnResult(2, $e->getMessage());
    	}
    }
    
}