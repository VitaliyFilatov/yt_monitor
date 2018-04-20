<?php
defined('SYSPATH') or die('No direct script access.');

class Service_Pattern
{
    protected static function getSubtitleByVideId($videoId)
    {
        //request on getting link for download file with subtitle of video by id
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($ch, CURLOPT_URL, "http://www.yousubtitles.com/loadvideo/ch--" . $videoId);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding:gzip',
            'Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'Connection:keep-alive',
            'Content-Length:0',
            'Cookie:_ym_uid=1520017726541143244; _ym_visorc_40164390=w; _ym_isad=2; __atuvc=3%7C9; __atuvs=5a99a13ed5f885d7002',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
        curl_setopt($ch, CURLOPT_POST, 1);
        
        
        
        $output = curl_exec($ch);
        curl_close($ch);
        try 
        {
        	$output = gzdecode($output);
        }
        catch(Exception $e)
        {
        	return array('type'=>0, 'result'=>0);
        }
        if($output === false)
        {
        	return array('type'=>0, 'result'=>0);
        }
        $response = json_decode($output);
        //'load' is field from json
        $downloadlink = $response->{'load'};
        //6 it is length string 'href="'
        $downloadlink = substr($downloadlink,
            strpos($downloadlink, 'href') + 6,
            strpos($downloadlink, '"', strpos($downloadlink, 'href') + 6) - strpos($downloadlink, 'href') - 6);
        
        
        $downloadlink = 'http://www.yousubtitles.com' . $downloadlink;
        
        //request on getting file with subtitle by link
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $downloadlink);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding:gzip',
            'Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'Connection:keep-alive',
            'Content-Length:0',
            'Cookie:_ym_uid=1520017726541143244; _ym_visorc_40164390=w; _ym_isad=2; __atuvc=3%7C9; __atuvs=5a99a13ed5f885d7002',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
        
        $output = curl_exec($ch);
        curl_close($ch);
        $output = gzdecode($output);
        return array('type'=>1, 'result'=>$output);
    }
    
    
    protected static function getContentAnaliz($text, $strdelimeter='\n', $itemdelimeter=' ')
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($ch, CURLOPT_URL, 'https://istio.com/rus/text/analyz#top');
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: istio.com',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate, br',
            'Referer: https://istio.com/rus/text/analyz',
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: PHPSESSID=671587821de9db51fc6084117a0dcf9b; _ym_uid=1520029746795772207; _ym_isad=2; _ym_visorc_27722736=w',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'Pragma: no-cache',
            'Cache-Control: no-cache'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('ContentForm[content]' => $text,
            'stat_word' => 'Анализ текста')));
        curl_setopt($ch, CURLOPT_POST, 1);
        
        
        
        
        $res = curl_exec($ch);
        if($res == false)
        {
        	$err = curl_error($ch);
        	$errno = curl_errno($ch);
        }
        curl_close($ch);
        $output = gzdecode($res);
        if($output === false)
        {
        	return false;
        }
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($output);
        libxml_use_internal_errors(false);
        $tabpanel = $dom->getElementById('words');
        $table = $tabpanel->getElementsByTagName('table')->item(0);
        $table->removeChild($table->getElementsByTagName('thead')->item(0));
        $trs = $table->getElementsByTagName('tr');
        $tds;
        $result;
        $data='';
        for($i=0; $i<$trs->length; $i++)
        {
            $tds = $trs->item($i)->getElementsByTagName('td');
            $result='';
            for($j=0; $j<$tds->length; $j++)
            {
                $result.=$tds->item($j)->textContent . $itemdelimeter;
            }
            $data .= $result . $strdelimeter;
        };
        
        
        return $data;
    }
    
    
    //result function - array: word => part of this word in text
    static function parseContentAnalize($text, $strdelimeter='\n', $itemdelimeter = ' ')
    {
        $words = explode($strdelimeter, $text);
        $wordsstat=array();
        $sum = 0;
        foreach($words as $str)
        {
            $items = explode($itemdelimeter, $str);
            if(count($items) == 7)
            {
                $wordsstat[$items[1]] = $items[2];
                $sum += $items[2];
            }
        }
        
//         foreach ($wordsstat as $key=>$value)
//         {
//             $wordsstat[$key] = $value / $sum;
//         }
        
        return $wordsstat;
    }
    
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
   
    
    function getTextSimilarity($pattern, $diff, $resCA)
    {
        //$dif = array();
        $dif = 0;
        $maxDif = 0;
        $i = 0;
        $freq;
        foreach($pattern as $key=>$value)
        {
            if(!array_key_exists($key, $resCA))
            {
                $freq = 0;
            }
            else
            {
                $freq = $resCA[$key];
            }
            //$dif[$i] = pow($freq - $pattern[$key], 2) * $pattern[$key] * $diff[$key];
            $dif += pow($freq - $pattern[$key], 2) * $pattern[$key] * $diff[$key];
            $maxDif += pow($pattern[$key],3) * $diff[$key];
            $i++;
        }
        
        return 1 - $dif / $maxDif;
    }
    
    public static function getSimilarityWithPattern($pattern, $resCA)
    {
        $dif = 0;
        $maxDif = 0;
        $i = 0;
        $freq;
        //$pattern->words[1]["word"];
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
            //$dif[$i] = pow($freq - $pattern[$key], 2) * $pattern[$key] * $diff[$key];
//             $var = pow($freq - $words["frequency"], 2);
//             $var = pow($freq - $words["frequency"], 2) * $words["frequency"];
             //$var1 = pow($freq - $words["frequency"], 2) * $words["frequency"] * $words["dif_frequency"];
//             $var = pow($words["frequency"],3);
             //$var2 = pow($words["frequency"],3) * $words["dif_frequency"];
            if($freq < $words["frequency"])
            {
            	$dif += pow($freq - $words["frequency"], 2) * $words["frequency"] * $words["dif_frequency"];
            }
            $maxDif += pow($words["frequency"],3) * $words["dif_frequency"];
            $i++;
        }
        
        
        return 1 - $dif / $maxDif;
    }

    public function createPattern($patternName, $videoIds, $queue)
    {
        $genWords = $this->getGeneralWords("C:\\Server\\data\\htdocs\\yt_monitor\\files\\lemma.num");
        $videoIdsWithSubtls = array();
        $wordsstat = array();
        $i=0;
        foreach($videoIds as $videoId)
        {
        	$i++;
            $subtitle = $this->getSubtitleByVideId($videoId);
            if($subtitle['type'] === 0)
            {
            	return array('type'=>0, 'result'=>$videoId);
            }
            $subtitle = $subtitle['result'];
            array_push($wordsstat, $this->parseContentAnalize($this->getContentAnaliz($subtitle)));
            array_push($videoIdsWithSubtls, $videoId);
            $value = round($i/count($videoIds)*100);
            while(strlen($value) < 3)
            {
            	$value = "0" . $value;
            }
            $queue->push($value);
            
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
        return array('type'=>1, 'result'=>$patternEntity);
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
        $subtitle = Service_Pattern::getSubtitleByVideId($videoId);
        if($subtitle['type'] == 0)
        {
        	return "nosub";
        }
        else 
        {
        	$subtitle = $subtitle['result'];
        	$resCA = Service_Pattern::getStatistics(array(Service_Pattern::parseContentAnalize(Service_Pattern::getContentAnaliz($subtitle))));
        	return Service_Pattern::getSimilarityWithPattern($pattern, $resCA);
        }
        
        
    }
    
    public static function getPatternById($id)
    {
        $pattern = new Pattern($id);
        $word1 = $pattern->words[0]["word"];
        $word2 = $pattern->words[1]["word"];
        $word1 = $word1;
    }
    
    /**
     *
     * @param Service_Queue $queue
     * @param Service_Queue $stopqueue
     * @param Service_Queue $pausequeue
     * @param Service_Queue $channelsqueue
     */
    public static function analizeChannel($request, $session, $channelId, $patternId, $queue, $stopqueue, $pausequeue, $channelsqueue)
    {
        $apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
            'oMbF7Zj1K9cCVXw3ZVGFN5z-');
        try
        {
            $apiServicre->authorize("analyze", $request, $session);
            $htmlBody = $apiServicre->getChannelsVideo($session, $channelId);
            if($htmlBody['return_type'] == 1)
            {
                return $htmlBody;
            }
            else 
            {
            	foreach ($htmlBody['result'] as $key=>$videoId)
            	{
            		$stop = $stopqueue->pop(5);
            		if($stop == "astop")
            		{
            			return array('return_type' => 0, 'result' => "true");
            		}
            		$pause = $pausequeue->pop(5);
            		if($pause== "pause")
            		{
            			$sliced = array_slice($htmlBody['result'], $key);
            			$slicedstr = implode(",", $sliced);
            			$length = strlen($slicedstr);
            			while(strlen($length)<5)
            			{
            				$length = "0" . $length;
            			}
            			$patternStr = $patternId;
            			while(strlen($patternStr)<10)
            			{
            				$patternStr = "0" . $patternStr;
            			}
            			$pausequeue->push($length . $patternStr . $slicedstr);
            			return array('return_type' => 0, 'result' => "true");
            		}
            		if($videoId == null)
            		{
            			continue;
            		}
            		$sim = Service_Pattern::analizeVideo($videoId, new Pattern($patternId));
            		if($sim != "nosub")
            		{
            			if($sim < 0.001)
            			{
            				$sim = 0;
            			}
            			$sim = substr($sim, 0, 5);
            		}
            		while(strlen($sim) < 5)
            		{
            			$sim = " " . $sim;
            		}
            		$toqueue = $videoId. substr($sim, 0, 5);
            		$queue->push($toqueue);
            	}
            	return array('return_type' => 0, 'result' => "true");
            }
        }
        catch(Exception $e)
        {
        	return array('return_type' => 2, 'result' => $e->getMessage());
        }
    }
    
}