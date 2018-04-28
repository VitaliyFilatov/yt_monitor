<?php defined('SYSPATH') or die('No direct script access.');
//

class Controller_Welcome extends Controller_Template {

	public $template = 'MainTemplate';
	
	public $links = array(0 => array('id'=>'',
			'active' => '',
			'href'=>'index',
			'text'=>'Генерация паттерна'),
			1 => array('id'=>'',
					'active' => '',
					'href'=>'analyze',
					'text'=>'Анализ каналов'),
			2 => array('id'=>'',
					'active' => '',
					'href'=>'monitor',
					'text'=>'Мониторинг каналов'));

	public function action_index()
	{
		$date = new DateTime();
		$data = Session::instance()->id() . $date->getTimestamp();
		$content = View::factory('Prepare');
		$this->links[0]['id'] = 'reload';
		$this->links[0]['active'] = 'active';
		$this->template->sessionid = $data;
		$this->template->links = $this->links;
		$this->template->content = $content;
		$this->template->scripts = array("media/js/prepare.js");
	}
	
	public function action_analyze()
	{
		$date = new DateTime();
		$data = Session::instance()->id() . $date->getTimestamp();
		$patternPanel = View::factory('PatternPanel');
		$channelPanelBody = View::factory('channelPanelBody');
		$content = View::factory('Analyze');
		$content->patternPanel = $patternPanel;
		$content->channelPanelBody= $channelPanelBody;
		$this->links[1]['id'] = 'reload';
		$this->links[1]['active'] = 'active';
		$this->template->sessionid = $data;
		$this->template->links = $this->links;
		$this->template->content = $content;
		$this->template->scripts = array("media/js/cookie-manager.js",
				"media/js/pattern-panel.js",
				"media/js/channel-panel.js",
				"media/js/analyze.js");
	}
	
	public function action_monitor()
	{
		$date = new DateTime();
		$data = Session::instance()->id() . $date->getTimestamp();
		$patternPanel = View::factory('PatternPanel');
		$channelPanelBody = View::factory('channelPanelBody');
		$content = View::factory('Monitor');
		$content->patternPanel = $patternPanel;
		$content->channelPanelBody= $channelPanelBody;
		$this->links[2]['id'] = 'reload';
		$this->links[2]['active'] = 'active';
		$this->template->sessionid = $data;
		$this->template->links = $this->links;
		$this->template->content = $content;
		$this->template->scripts = array("media/js/cookie-manager.js",
				"media/js/pattern-panel.js",
				"media/js/channel-panel.js",
				"media/js/monitor.js");
	}

} // End Welcome
