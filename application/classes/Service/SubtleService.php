<?php
defined('SYSPATH') or die('No direct script access.');

class Service_SubtleService
{
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
			return new Entity_ReturnResult(0,0);
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
				return new Entity_ReturnResult(0, 0);
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
				
				$filename = APPPATH . "subtle_files/" .
						$videoId . ".txt";
						
						$file = fopen($filename, "a");
						fclose($file);
						file_put_contents($filename, $subtles);
						return new Entity_ReturnResult(1, $subtles);
			}
			catch(Exception $e)
			{
				return new Entity_ReturnResult(0, 0);
			}
		}
		catch(Exception $e)
		{
			return new Entity_ReturnResult(0, 0);
		}
	}
	
	public static function getSubtitleByVideId($videoId)
	{
		$filename = APPPATH ."subtle_files/" .
				$videoId .
				".txt";
				if(!file_exists($filename))
				{
					return Service_SubtleService::getSubtitleFromSrvice($videoId);
				}
				return new Entity_ReturnResult(1, file_get_contents($filename));
	}
}