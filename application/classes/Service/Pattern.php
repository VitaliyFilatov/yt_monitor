<?php
defined('SYSPATH') or die('No direct script access.');

class Service_Pattern
{
	private static $gap="gapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgap";
	
	private static $sentThreshold = 0.77;
	
	private static $max_timeout = 10;
	
	private static $proxyIP = "185.158.112.209";
	
	private static $proxyPort = "3128";
	
// 	protected static function getSubtitleFromSrvice($videoId)
// 	{
// 		//request on getting link for download file with subtitle of video by id
// 		$ch = curl_init();
		
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
// 		curl_setopt($ch, CURLOPT_URL, "http://www.yousubtitles.com/loadvideo/ch--" . $videoId);
// 		//Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7
// 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
// 		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// 				'Accept:application/json, text/javascript, */*; q=0.01',
// 				'Accept-Encoding:gzip',
// 				'Accept-Language:ru-RU,ru;q=0.9',
// 				'Connection:keep-alive',
// 				'Content-Length:0',
// 				'Cookie: __atuvc=11%7C19; _ym_uid=152590281312647416; __atuvs=5af6c82fa1d7c590000; _ym_visorc_40164390=w; _ym_isad=2',
// 				'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
// 		curl_setopt($ch, CURLOPT_POST, 1);
		
// 		curl_setopt($ch, CURLOPT_PROXY, Service_Pattern::$proxyIP);
// 		curl_setopt($ch, CURLOPT_PROXYPORT, Service_Pattern::$proxyPort);
		
// 		$output = curl_exec($ch);
// 		if($output === false)
// 		{
// 			$err = curl_error($ch);
// 			$errno = curl_errno($ch);
// 			return array('type'=>0, 'result'=>0);
// 		}
// 		curl_close($ch);
// 		try
// 		{
// 			$output = gzdecode($output);
// 		}
// 		catch(Exception $e)
// 		{
// 			return array('type'=>0, 'result'=>0);
// 		}
// 		$response = json_decode($output);
// 		//'load' is field from json
// 		$downloadlink = $response->{'links'};
// 		//6 it is length string 'href="'
// 		$downloadlink = substr($downloadlink,
// 				strpos($downloadlink, '<a href=') + 10,
// 				strpos($downloadlink, '"', strpos($downloadlink, '<a href=') + 10) - strpos($downloadlink, '<a href=') - 10);
		
// 		$downloadlink = substr_replace($downloadlink,
// 				"ru",
// 				strpos($downloadlink, 'lang%3D') + 7,
// 				strpos($downloadlink, '&title')  - strpos($downloadlink, 'lang%3D') - 7);
		
// 		$downloadlink = 'http://www.yousubtitles.com/' . $downloadlink;
		
// 		//request on getting file with subtitle by link
// 		$ch = curl_init();
		
// 		curl_setopt($ch, CURLOPT_URL, $downloadlink);
		
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
// 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
// 		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
		
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// 				'Accept:application/json, text/javascript, */*; q=0.01',
// 				'Accept-Encoding:gzip',
// 				'Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
// 				'Connection:keep-alive',
// 				'Content-Length:0',
// 				'Cookie: __atuvc=11%7C19; _ym_uid=152590281312647416; __atuvs=5af6c82fa1d7c590000; _ym_visorc_40164390=w; _ym_isad=2',
// 				'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
// 		curl_setopt($ch, CURLOPT_PROXY, Service_Pattern::$proxyIP);
// 		curl_setopt($ch, CURLOPT_PROXYPORT, Service_Pattern::$proxyPort);
		
// 		$output = curl_exec($ch);
// 		curl_close($ch);
// 		if(empty($output))
// 		{
// 			$downloadlink= substr_replace($downloadlink,"kind%3Dasr%26",strpos($downloadlink, 'expire%26')+9,0);
// 			$ch = curl_init();
			
// 			curl_setopt($ch, CURLOPT_URL, $downloadlink);
			
// 			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// 			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
// 			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
// 			curl_setopt($ch, CURLOPT_TIMEOUT, 400);
			
// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// 					'Accept:application/json, text/javascript, */*; q=0.01',
// 					'Accept-Encoding:gzip',
// 					'Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
// 					'Connection:keep-alive',
// 					'Content-Length:0',
// 					'Cookie: __atuvc=11%7C19; _ym_uid=152590281312647416; __atuvs=5af6c82fa1d7c590000; _ym_visorc_40164390=w; _ym_isad=2',
// 					'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
			
// 			curl_setopt($ch, CURLOPT_PROXY, Service_Pattern::$proxyIP);
// 			curl_setopt($ch, CURLOPT_PROXYPORT, Service_Pattern::$proxyPort);
			
// 			$output = curl_exec($ch);
// 			curl_close($ch);
// 		}
// 		try
// 		{
// 			$output = gzdecode($output);
// 		}
// 		catch(Exception $e)
// 		{
// 			return array('type'=>0, 'result'=>0);
// 		}
// 		$filename = "C:\\Server\\data\\htdocs\\yt_monitor\\application\\subtle_files\\" .
// 				$videoId . ".txt";
		
// 		$file = fopen($filename, "a");
// 		fclose($file);
// 		file_put_contents($filename, $output);
// 		return array('type'=>1, 'result'=>$output);
// 	}

	protected static function getSubtitleFromSrvice($videoId)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/watch?v=" . $videoId);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
				'Accept-Encoding:gzip',
				'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
				'Connection:keep-alive',
				'Cookie: VISITOR_INFO1_LIVE=lO1LV2Cgcoo; YSC=_JJyOVysdSY; PREF=f1=50000000',
				'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
		
		
		$output = curl_exec($ch);
		if($output === false)
		{
			$err = curl_error($ch);
			$errno = curl_errno($ch);
			return array('type'=>0, 'result'=>0);
		}
		curl_close($ch);
		try
		{
			$output = gzdecode($output);
			$param = substr($output,
					strpos($output, '"getTranscriptEndpoint":{"params":') + 35,
					strpos($output, '"', strpos($output, '"getTranscriptEndpoint":{"params":') + 35) - strpos($output, '"getTranscriptEndpoint":{"params":') - 35);
			
			$xsrftoken = substr($output,
					strpos($output, 'XSRF_TOKEN":"') + 13,
					strpos($output, '"', strpos($output, 'XSRF_TOKEN":"') + 13) - strpos($output, 'XSRF_TOKEN":"') - 13);
			
			$xsrftoken = urlencode($xsrftoken);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/service_ajax?name=getTranscriptEndpoint");
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 400);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
					'Accept-Encoding:gzip',
					'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
					'Connection:keep-alive',
					'Cookie: VISITOR_INFO1_LIVE=lO1LV2Cgcoo; YSC=_JJyOVysdSY; PREF=f1=50000000',
					'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "sej=%7B%22clickTrackingParams%22%3A%22CFAQzKsBGAAiEwjsmum4oIDbAhUnFJsKHUbtBeUo-B0%3D%22%2C%22commandMetadata%22%3A%7B%22webCommandMetadata%22%3A%7B%22url%22%3A%22%2Fservice_ajax%22%2C%22sendPost%22%3Atrue%7D%7D%2C%22getTranscriptEndpoint%22%3A%7B%22params%22%3A%22".$param."%253D%22%7D%7D&csn=qqq&session_token=".$xsrftoken);
			curl_setopt($ch, CURLOPT_POST, 1);
			$output = curl_exec($ch);
			if($output === false)
			{
				return array('type'=>0, 'result'=>0);
			}
			try 
			{
				$output = gzdecode($output);
				$data = json_decode($output);
				$data = $data->{'data'}->
				{'actions'}[0]->
				{'openTranscriptAction'}->
				{'transcriptRenderer'}->
				{'transcriptRenderer'}->
				{'body'}->
				{'transcriptBodyRenderer'}->
				{'cueGroups'};
				$subtles = "";
				foreach($data as $t)
				{
					$subtles = $subtles . $t->{'transcriptCueGroupRenderer'}->
					{'cues'}[0]->
					{'transcriptCueRenderer'}->
					{'cue'}->
					{'runs'}[0]->
					{'text'} . "\n";
				}
				
				$filename = "C:\\Server\\data\\htdocs\\yt_monitor\\application\\subtle_files\\" .
						$videoId . ".txt";
						
						$file = fopen($filename, "a");
						fclose($file);
						file_put_contents($filename, $subtles);
						return array('type'=>1, 'result'=>$subtles);
			}
			catch(Exception $e)
			{
				return array('type'=>0, 'result'=>0);
			}
		}
		catch(Exception $e)
		{
			return array('type'=>0, 'result'=>0);
		}
	}
	
	protected static function getSubtitleByVideId($videoId)
	{
	    	$filename = "C:\\Server\\data\\htdocs\\yt_monitor\\application\\subtle_files\\" .
	      	$videoId .
	      	".txt";
	    	if(!file_exists($filename))
	    	{
	    		return Service_Pattern::getSubtitleFromSrvice($videoId);
	    	}
	    	return array('type'=>1, 'result'=>file_get_contents($filename));
	}
    
    
	protected static function getServiceContentAnaliz($text, $videoId, $strdelimeter='\n', $itemdelimeter=' ')
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
        if(strlen($text) < 100)
        {
        	$text .= " " . Service_Pattern::$gap;
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('ContentForm[content]' => $text,
            'stat_word' => 'Анализ текста')));
        curl_setopt($ch, CURLOPT_POST, 1);
        
//         curl_setopt($ch, CURLOPT_PROXY, "1.179.183.86");
//         curl_setopt($ch, CURLOPT_PROXYPORT, "8080");
        
        
        
        
        $res = curl_exec($ch);
        if($res == false)
        {
        	$err = curl_error($ch);
        	$errno = curl_errno($ch);
        	return false;
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
            	if(strcmp($tds->item($j)->textContent, Service_Pattern::$gap) != 0)
            	{
            		$result.=$tds->item($j)->textContent . $itemdelimeter;
            	}
            }
            $data .= $result . $strdelimeter;
        };
        
        $filename = "C:\\Server\\data\\htdocs\\yt_monitor\\application\\content_analyze\\" .
          $videoId . ".txt";
          
          $file = fopen($filename, "a");
          fclose($file);
          file_put_contents($filename, $data);
        
        return $data;
    }
    
    protected static function getContentAnaliz($text, $videoId, $strdelimeter='\n', $itemdelimeter=' ')
    {
    	$filename = "C:\\Server\\data\\htdocs\\yt_monitor\\application\\content_analyze\\" .
      	$videoId .
      	".txt";
      	if(!file_exists($filename))
      	{
      		$res = Service_Pattern::getServiceContentAnaliz($text,
      				$videoId,
      				$strdelimeter,
      				$itemdelimeter);
      		return Service_Pattern::getServiceContentAnaliz($text,
      				$videoId,
      				$strdelimeter,
      				$itemdelimeter);
      	}
      	$res = file_get_contents($filename);
      	return file_get_contents($filename);
    }
    
    protected static function getContentAnalyzeIstio($text)
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
    	if(strlen($text) < 100)
    	{
    		$text .= " " . Service_Pattern::$gap;
    	}
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
    	try {
    		$output = gzdecode($res);
    	}
    	catch(Exception $e)
    	{
    		return array();
    	}
    	if($output === false)
    	{
    		return array();
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
    	$words = array();
    	for($i=0; $i<$trs->length; $i++)
    	{
    		$tds = $trs->item($i)->getElementsByTagName('td');
    		if($tds->length == 6)
    		{
    			if(strcmp($tds->item(1)->textContent, Service_Pattern::$gap) != 0)
    			{
    				$word = new Entity_BagWords();
    				$word->word = $tds->item(1)->textContent;
    				$word->freq = $tds->item(2)->textContent;
    				array_push($words, $word);
    			}
    		}
    	};
    	
    	
    	return $words;
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

    public function createPattern($patternName, $videoIds, $sessionid)
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
            array_push($wordsstat, $this->parseContentAnalize($this->getContentAnaliz($subtitle, $videoId)));
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
        return array('type'=>1, 'result'=>$patternEntity);
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
    	$sims = $sims['result'];
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
        $subtitle = Service_Pattern::getSubtitleByVideId($videoId);
        if($subtitle['type'] == 0)
        {
        	return "nosub";
        }
        else 
        {
        	$subtitle = $subtitle['result'];
        	$resCA = Service_Pattern::getStatistics(array(Service_Pattern::parseContentAnalize(Service_Pattern::getContentAnaliz($subtitle, $videoId))));
        	return Service_Pattern::getSimilarityWithPattern($pattern, $resCA);
        }
// 		$subtitle = "субтитры какие-то комментарии Путин";
// 		$resCA = Service_Pattern::getStatistics(array(Service_Pattern::parseContentAnalize(Service_Pattern::getContentAnaliz($subtitle))));
// 		return Service_Pattern::getSimilarityWithPattern($pattern, $resCA);
    }
    
    public static function getPatternById($id)
    {
        $pattern = new Pattern($id);
        $word1 = $pattern->words[0]["word"];
        $word2 = $pattern->words[1]["word"];
        $word1 = $word1;
    }
    
    public static function getInfo($request, $session, $videoId, $reirect, $channelId, $sessionid)
    {
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	try
    	{
    		$apiServicre->authorize($reirect, $request, $session);
    		$videoStatistics = $apiServicre->getVideoStatistics($session, $videoId);
    		if($videoStatistics['return_type'] !== 0)
    		{
    			return $videoStatistics;
    		}
    		$videoStatistics = $videoStatistics['result'];
    		$channelStatistics = $apiServicre->getChannelStatistics($session, $channelId);
    		if($channelStatistics['return_type'] !== 0)
    		{
    			return $channelStatistics;
    		}
    		$channelStatistics = $channelStatistics['result'];
    	}
    	catch(Exception $e)
    	{
    		return array('return_type' => 2, 'result' => $e->getMessage());
    	}
    	$result = Service_Pattern::sentimentComments($request, $session, $videoId, $sessionid);
    	if($result['return_type'] == 0)
    	{
    		$sumpos = 0;
    		$sumneg = 0;
    		foreach($result['result'] as $sentiment)
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
    	if($videoStatistics->viewCount > 0)
    	{
    		$videoInfo->ERbyviews = ($videoStatistics->likeCount +
    				$videoStatistics->commentCount +
    				$videoStatistics->dislikeCount)/$videoStatistics->viewCount;
    	}
    	else
    	{
    		$videoInfo->ERbyviews = 0;
    	}
    	
    	if($channelStatistics->subscriberCount > 0)
    	{
    		$videoInfo->ERpost = ($videoStatistics->likeCount +
    				$videoStatistics->commentCount +
    				$videoStatistics->dislikeCount)/$channelStatistics->subscriberCount;
    	}
    	else 
    	{
    		$videoInfo->ERpost = 0;
    	}
    	
    			
    	$videoInfo->positiveCount = $sumpos;
    	$videoInfo->negativeCount = $sumneg;
    	
    	return array('return_type' => 0, 'result' => $videoInfo);
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
    				return array('return_type' => 3, 'result' => $sessionid);
    			}
    		}
    	}
    	$previoustimestamp = $lasttimestamp;
    	if(Model_StopAnalyze::isStop($sessionid))
    	{
    		Model_Result::popAllResults($sessionid);//clear result table
    		return array('return_type' => 3, 'result' => $sessionid);
    	}
    	return array('return_type' => 0, 'result' => $previoustimestamp);
    }
    
    
    public static function analizeVideosWithChannels($videos, $patternId, $sessionid, $request, $session)
    {
    	$pattern = new Pattern($patternId);
    	$previoustimestamp = null;
    	foreach ($videos as $key=>$video)
    	{
    		$isStop = Service_Pattern::isStop($sessionid, $previoustimestamp);
    		if($isStop['return_type'] === 0)
    		{
    			$previoustimestamp = $isStop['result'];
    		}
    		else if($isStop['return_type'] === 3)
    		{
    			return $isStop;
    		}
    		if(Model_PauseAnalyze::isPause($sessionid))
    		{
    			$sliced = array_slice($videos, $key);
    			Model_SaveResult::addResultWithChannels($sessionid, $sliced, $patternId);
    			return array('return_type' => 4, 'result' => $sessionid);
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
    			if($videoInfo['return_type'] == 1)
    			{
    				return $videoInfo;
    			}
    			else if($videoInfo['return_type'] == 0)
    			{
    				Model_Result::addResultWithInfo($sessionid,
    						$video['videoId'],
    						$sim,
    						$videoInfo['result']->ERpost,
    						$videoInfo['result']->ERbyviews,
    						$videoInfo['result']->positiveCount,
    						$videoInfo['result']->negativeCount);
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
    	return array('return_type' => 0, 'result' => "true");
    }
    
    public static function analizeVideos($videoIds, $patternId, $sessionid, $request, $session, $channelId)
    {
    	$pattern = new Pattern($patternId);
    	foreach ($videoIds as $key=>$videoId)
    	{
    		if(Model_StopAnalyze::isStop($sessionid))
    		{
    			return array('return_type' => 3, 'result' => "true");
    		}
    		if(Model_PauseAnalyze::isPause($sessionid))
    		{
    			$sliced = array_slice($videoIds, $key);
    			Model_SaveResult::addResult($sessionid, $sliced, $patternId);
    			return array('return_type' => 4, 'result' => $sessionid);
    		}
    		if($videoId == null)
    		{
    			continue;
    		}
    		$sim = Service_Pattern::analizeVideo($videoId, $pattern);
    		if($sim == "nosub")
    		{
    			$sim = -1;
    		}
    		if($pattern->threshold <= $sim)
    		{
    			$videoInfo = Service_Pattern::getInfo($request, $session, $videoId, "authorize", $channelId, $sessionid);
    			if($videoInfo['return_type'] == 1)
    			{
    				return $videoInfo;
    			}
    			else if($videoInfo['return_type'] == 0)
    			{
    				Model_Result::addResultWithInfo($sessionid,
    						$videoId,
    						$sim,
    						$videoInfo['result']->ERpost,
    						$videoInfo['result']->ERbyviews,
    						$videoInfo['result']->positiveCount,
    						$videoInfo['result']->negativeCount);
    			}
    			else {
    				Model_Result::addResult($sessionid, $videoId, $sim);
    			}
    		}
    		else
    		{
    			Model_Result::addResult($sessionid, $videoId, $sim);
    		}
    	}
    	return array('return_type' => 0, 'result' => "true");
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
    	return array('return_type' => 0, 'result' => $sims);
    }
    
    public static function analizeMonitorVideos($videoIds, $patternId, $sessionid)
    {
    	foreach ($videoIds as $key=>$videoId)
    	{
    		if(Model_StopAnalyze::isStop($sessionid))
    		{
    			return array('return_type' => 0, 'result' => "true");
    		}
    		if(Model_PauseAnalyze::isPause($sessionid))
    		{
    			$sliced = array_slice($videoIds, $key);
    			Model_SaveResult::addResult($sessionid, $sliced, $patternId);
    			return array('return_type' => 0, 'result' => $sessionid);
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
    	return array('return_type' => 0, 'result' => "true");
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
    			if($htmlBody['return_type'] !== 0)
    			{
    				return $htmlBody;
    			}
    			else 
    			{
    				foreach($htmlBody['result'] as $videoId)
    				{
    					array_push($videos, array('videoId'=>$videoId, 'channelId'=>$channelId));
    				}
    			}
    		}
    		return Service_Pattern::analizeVideosWithChannels($videos, $patternId, $sessionid, $request, $session);
    	}
    	catch(Exception $e)
    	{
    		return array('return_type' => 2, 'result' => $e->getMessage());
    	}
    }
    
    
    public static function analizeChannel($request, $session, $channelId, $patternId, $sessionid)
    {
        $apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
            'oMbF7Zj1K9cCVXw3ZVGFN5z-');
        try
        {
            $apiServicre->authorize("authorize", $request, $session);
            $htmlBody = $apiServicre->getChannelsVideo($session, $channelId);
            if($htmlBody['return_type'] !== 0)
            {
                return $htmlBody;
            }
            else 
            {
            	return Service_Pattern::analizeVideos($htmlBody['result'], $patternId, $sessionid, $request, $session, $channelId);
            }
        }
        catch(Exception $e)
        {
        	return array('return_type' => 2, 'result' => $e->getMessage());
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
    			if($htmlBody['return_type'] !== 0)
    			{
    				return $htmlBody;
    			}
    			else 
    			{
    				if($htmlBody['result'][0] == $lastVideoId)
    				{
    					return array('return_type' => 0, 'result' => null);
    				}
    				$videoIds = array_merge($videoIds, $htmlBody['result']);
    			}
    		}
    		if(!empty($videoIds))
    		{
    			Service_Pattern::analizeMonitorVideos($videoIds, $patternId, $sessionid);
    			return array('return_type' => 0,
    					'result' => Model_MonitorResult::popAllResults($sessionid));
    		}
    		return array('return_type' => 0, 'result' => null);
    	}
    	catch(Exception $e)
    	{
    		return array('return_type' => 2, 'result' => $e->getMessage());
    	}
    }
    
    
    public static function sentimentComments($request, $session, $videoId, $sessionid)
    {
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	
    	try
    	{
    		$apiServicre->authorize("authorize", $request, $session);
    		$comments = Model_VideoComment::getVideoComments($videoId);
    		if(empty($comments))
    		{
    			$htmlBody = $apiServicre->getVideoComment($session, $videoId);
    			if($htmlBody['return_type'] !== 0)
    			{
    				return $htmlBody;
    			}
    			Model_VideoComment::addComments($videoId, $htmlBody['result']);
    		}
    		else 
    		{
    			$htmlBody = array('return_type'=>0, 'result'=>$comments);
    		}
    		$sentiment = array();
    		foreach($htmlBody['result'] as $i=>$comment)
    		{
    			if($i>=100)
    			{
    				break;
    			}
    			$isStop = Service_Pattern::isStop($sessionid);
    			if($isStop['return_type'] === 3)
    			{
    				return $isStop;
    			}
    			$words = Service_Pattern::getContentAnalyzeIstio($comment);
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
    		return array('return_type' => 0, 'result' => $sentiment);
    	}
    	catch(Exception $e)
    	{
    		return array('return_type' => 2, 'result' => $e->getMessage());
    	}
    }
    
    public static function prepareBagOfWords($request, $session)
    {
//     	$file = file(trim("C:\\Users\\user\\Downloads\\negative.txt"));
//     	$text = implode(" ", $file);
    	$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
    			'oMbF7Zj1K9cCVXw3ZVGFN5z-');
    	
    	try
    	{
    		$apiServicre->authorize("authorize", $request, $session);
    		$htmlBody = $apiServicre->getVideoComment($session, "JkUGDu2HNv0");
    		$sentiment = array();
    		if($htmlBody['return_type'] !== 0)
    		{
    			return $htmlBody;
    		}
    		else
    		{
    			foreach($htmlBody['result'] as $i=>$comment)
    			{
    				if($i>=100)
    				{
    					break;
    				}
    				$words = Service_Pattern::getContentAnalyzeIstio($comment);
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
    		return array('return_type' => 0, 'result' => null);
    	}
    	catch(Exception $e)
    	{
    		return array('return_type' => 2, 'result' => $e->getMessage());
    	}
    }
    
}