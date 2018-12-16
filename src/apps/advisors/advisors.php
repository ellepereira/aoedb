<?php

class advisors extends app
{
	function __construct(&$parent)
	{
		parent::__construct($parent);

		$this->load->app('aoeo');
		$this->config = $this->aoeo->config;
		$this->load->model('advisor');
	}
	
	public function c_index($name = null)
	{
		
		if(!empty($name))
		{
			$ad = $this->m_advisor->get($name);
			
			$this->aoeo->header($ad['displayname']);
			$this->show('advisor', $ad);
			
			$this->aoeo->load->view('ad_temp');
		
		}
		else
		{
			$this->aoeo->header('Advisors');
			$data['age'] = 1;
			$data['advisors'] = $this->m_advisor->get_all_by_age('0', 'epic');
			$data['rarity'] = 'epic';	
			$this->show('advisorlist', $data);
			$this->aoeo->load->view('ad_temp');
		}
		
		$this->aoeo->footer();
	}
	
	public function c_age($age, $rarity='')
	{
		$this->aoeo->header(ucfirst($rarity).' Age '.$age.' Advisors');
		$data['age'] = $age;
		$age -=1;
		$data['advisors'] = $this->m_advisor->get_all_by_age($age, $rarity);
		$data['rarity'] = $rarity;	
		$this->show('advisorlist', $data);
		$this->aoeo->load->view('ad_temp');
		$this->aoeo->footer();
	}
	
	public function c_advisor($name)
	{
		$ad = $this->m_advisor->get($name);
		$this->aoeo->header($ad['displayname']);
		$this->show('advisorlist', $ad);
		$this->aoeo->load->view('ad_temp');
		$this->aoeo->footer();
	}
	
	public function c_update()
	{
		$this->m_advisor->db_update();
		$this->m_advisor->db_update_toc();
	}
}