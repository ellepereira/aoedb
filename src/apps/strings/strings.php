<?php

class strings extends app
{

	function __construct(&$parent)
	{
		parent::__construct($parent);

		$this->load->app('aoeo');
		$this->config = $this->aoeo->config;
		$this->load->model('string');
	}
	
	function c_index($stringid = null)
	{
    if ($stringid) {
      $this->m_string->load($stringid);
      $string = $this->m_string;
      header('Content-type: text');
      echo $string->string;
    }
	}	
	
	public function c_update()
	{
		$this->load->model('string');
		$this->m_string->db_update();
	}
}
?>