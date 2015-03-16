<?php

class auction extends app
{
	
	function __construct($parent = null)
	{
		parent::__construct($parent);
		
		$this->load->app('aoeo');
	}
	
	function header($title = 'Auction House')
	{
		$this->aoeo->header($title);
	}
	
	function footer()
	{
		$this->aoeo->footer();
	}
	
	
	function c_index()
	{
		
	}
	
	function c_item($dbid, $rlevel)
	{
		$level = $rlevel + 3;
		$this->load->app('traits');

		$this->traits->m_trait->load($dbid, $level);
		$data['item'] = $this->traits->m_trait->info;
		//show index page here
		$this->aoeo->load->view("tinyheader");
		$this->load->view("item", $data);
		$this->aoeo->load->view("tinyfooter");
	}
	
	
	
}


?>