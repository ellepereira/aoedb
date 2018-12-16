<?php
	class quests extends app
	{
		function __construct(&$parent)
		{
			parent::__construct($parent);
	
			$this->load->app('aoeo');
			$this->load->model('quest');
			//$this->config = $this->aoeo->config;
		}
		
		function c_index($qid=null)
		{
			if($qid)
			{
				$this->c_quest($qid);
			}
			else
			{
				$this->c_list();
			}
			
			
		}
		
		function c_list()
		{
			$this->header();
			$q = $this->m_quest->get_all();
			
			$this->load->view('list', $q);
			$this->footer();
		}
		
		function c_quest($qid)
		{
			$this->header();
			$q = $this->m_quest->load($qid);
			$this->show('quest', $q);
			$this->footer();
		}
		
		function header()
		{
			$this->aoeo->header();
		}
		
		function footer()
		{
			$this->aoeo->footer();
		}
		
		function c_raw()
		{
			echo '<pre>';
			
			print_r($this->m_quest->get_all());
				
			echo '</pre>';
		}
		
		function c_update()
		{
			$this->m_quest->db_update();
			$this->m_quest->db_update_toc();
			$this->m_quest->db_update_questgiversart();
		}
		
	}
	
	/**end of file*/