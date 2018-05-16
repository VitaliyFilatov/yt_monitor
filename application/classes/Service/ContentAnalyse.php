<?php
defined('SYSPATH') or die('No direct script access.');

class Service_ContentAnalyse
{
	private static $gap="gapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgapgap";
	
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
			$text .= " " . Service_ContentAnalyse::$gap;
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
				if(strcmp($tds->item($j)->textContent, Service_ContentAnalyse::$gap) != 0)
				{
					$result.=$tds->item($j)->textContent . $itemdelimeter;
				}
			}
			$data .= $result . $strdelimeter;
		};
		
		$filename = APPPATH . "content_analyze/" .
				$videoId . ".txt";
				
				$file = fopen($filename, "a");
				fclose($file);
				file_put_contents($filename, $data);
				
				return $data;
	}
	
	public static function getContentAnaliz($text, $videoId, $strdelimeter='\n', $itemdelimeter=' ')
	{
		$filename = APPPATH . "content_analyze/" .
				$videoId .
				".txt";
				if(!file_exists($filename))
				{
					return Service_ContentAnalyse::getServiceContentAnaliz($text,
							$videoId,
							$strdelimeter,
							$itemdelimeter);
				}
				$res = file_get_contents($filename);
				return file_get_contents($filename);
	}
	
	public static function getContentAnalyzeIstio($text)
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
			$text .= " " . Service_ContentAnalyse::$gap;
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
				if(strcmp($tds->item(1)->textContent, Service_ContentAnalyse::$gap) != 0)
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
	
	
	public static function parseContentAnalize($text, $strdelimeter='\n', $itemdelimeter = ' ')
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
		return $wordsstat;
	}
}