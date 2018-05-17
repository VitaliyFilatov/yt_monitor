<?php
('SYSPATH') or die('No direct script access.');

/**
 * VieoInfo
 *
 * @author filatov
 *
 */
class Entity_VideoInfo
{
	
	
	/**
	 * count of like
	 *
	 * @var int
	 */
	public $like_count= 0;
	
	/**
	 * count of dislike
	 *
	 * @var int
	 */
	public $dislike_count= 0;
	
	/**
	 * count of positive comment
	 *
	 * @var int
	 */
	public $positive_count= 0;
	
	/**
	 * count of negative comment
	 *
	 * @var int
	 */
	public $negative_count= 0;
	
	/**
	 * count of views
	 *
	 * @var int
	 */
	public $view_count= 0;
	
	/**
	 * count of followers
	 *
	 * @var int
	 */
	public $followers_count= 0;
}
