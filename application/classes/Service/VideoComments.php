<?php
defined('SYSPATH') or die('No direct script access.');

class Service_VideoComments
{
	public static function getVideoComments($request, $session, $videoId, $maxCount)
	{
		$apiServicre = new Service_YTApi('1067254332521-4o8abvtsaj2sihjbj82qfa17j1vg8l6r.apps.googleusercontent.com',
				'oMbF7Zj1K9cCVXw3ZVGFN5z-');
		try
		{
			$apiServicre->authorize("authorize", $request, $session);
			$comments = Model_VideoComment::getVideoComments($videoId);
			if(empty($comments))
			{
				$htmlBody = $apiServicre->getVideoComment($session, $videoId, $maxCount);
				if($htmlBody->return_type !== 0)
				{
					return $htmlBody;
				}
				Model_VideoComment::addComments($videoId, $htmlBody->result);
			}
			else
			{
				$htmlBody = new Entity_ReturnResult(0, $comments);
			}
			return $htmlBody;
		}
		catch(Exception $e)
		{
			return new Entity_ReturnResult(2, $e->getMessage());
		}
	}
}