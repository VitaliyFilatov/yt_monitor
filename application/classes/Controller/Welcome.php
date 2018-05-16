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
					'text'=>'Мониторинг каналов'),
			3 => array('id'=>'',
					'active' => '',
					'href'=>'threshold',
					'text'=>'Пороговые зачения')
			);

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
		$channelPanelHeader= View::factory('channelPanelHeader');
		$resultBody = View::factory('ResultBody');
		$resultHeader = View::factory('ResultHeader');
		$content = View::factory('Analyze');
		$content->patternPanel = $patternPanel;
		$content->channelPanelBody= $channelPanelBody;
		$content->resultBody = $resultBody;
		$content->resultHeader = $resultHeader;
		$content->channelPanelHeader = $channelPanelHeader;
		$this->links[1]['id'] = 'reload';
		$this->links[1]['active'] = 'active';
		$this->template->sessionid = $data;
		$this->template->links = $this->links;
		$this->template->content = $content;
		$this->template->scripts = array("media/js/cookie-manager.js",
				"media/js/pattern-panel.js",
				"media/js/channel-panel.js",
				"media/js/result-body.js",
				"media/js/analyze.js");
	}
	
	public function action_monitor()
	{
		$date = new DateTime();
		$data = Session::instance()->id() . $date->getTimestamp();
		$patternPanel = View::factory('PatternPanel');
		$channelPanelBody = View::factory('channelPanelBody');
		$channelPanelHeader= View::factory('channelPanelHeader');
		$resultBody = View::factory('ResultBody');
		$resultHeader = View::factory('ResultHeader');
		$content = View::factory('Monitor');
		$content->patternPanel = $patternPanel;
		$content->channelPanelBody= $channelPanelBody;
		$content->resultBody = $resultBody;
		$content->resultHeader = $resultHeader;
		$content->channelPanelHeader = $channelPanelHeader;
		$this->links[2]['id'] = 'reload';
		$this->links[2]['active'] = 'active';
		$this->template->sessionid = $data;
		$this->template->links = $this->links;
		$this->template->content = $content;
		$this->template->scripts = array("media/js/cookie-manager.js",
				"media/js/pattern-panel.js",
				"media/js/channel-panel.js",
				"media/js/result-body.js",
				"media/js/monitor.js");
	}
	
	
	public function action_threshold()
	{
		$date = new DateTime();
		$data = Session::instance()->id() . $date->getTimestamp();
		$patternPanel = View::factory('PatternPanel');
		$content = View::factory('Threshold');
		$content->patternPanel = $patternPanel;
		$this->links[3]['id'] = 'reload';
		$this->links[3]['active'] = 'active';
		$this->template->sessionid = $data;
		$this->template->links = $this->links;
		$this->template->content = $content;
		$this->template->scripts = array("media/js/cookie-manager.js",
				"media/js/pattern-panel.js",
				"media/js/threshold.js"
		);
	}
	

} // End Welcome
