<?php

class materials extends app
{
	function __construct(&$parent)
	{
		parent::__construct($parent);

		//easy database handler
		$this->load->lib('easydb', $this->db);
		$this->load->app('aoeo');

		$this->config = $this->aoeo->config;
		$this->load->model('material');
		
			
	}
	
	public function c_index()
	{
		echo 'blank page!';
	}
	
	public function c_update()
	{
		$this->m_material->db_update();
	}
}
?>