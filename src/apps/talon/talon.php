<?php
class talon extends app
{
	
	function __construct(&$parent)
	{
		parent::__construct($parent);
		
		//constructor stuff, you can leave it empty
		
	}
	
	function c_index()
	{
		$this->c_sayhi('no one?');
		
	}
	
	function c_sayhi($to)
	{
		$data = array('name' => $to);
		$this->load->view('intro', $data);
		
	}
	
}

?>