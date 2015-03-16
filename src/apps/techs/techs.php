<?php

	class techs extends app
	{
		function __construct(&$parent)
		{
			parent::__construct($parent);
	
			$this->load->app('aoeo');
	
		}
		
		function c_index()
		{
			$this->aoeo->header();
			$this->aoeo->footer();
			
		}
		
	}
?>