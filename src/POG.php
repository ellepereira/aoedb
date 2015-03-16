<?php
class POG extends app
{
	public $apps;
	
	function __construct()
	{
		parent::__construct();
		//$this->config = $this->load->config('global');

		//need to crash better
		if(!isset($this->config['default_app']))
			exit;
	}
	
	function c_index()
	{	
		$app = $this->uri->app();
		$method = $this->uri->method();
		$params = $this->uri->params();
		
		//If the user didn't specify an app to load - we load the default one
		if(!isset($app))
			$app = $this->config['default_app'];
		
		if(!isset($method))
			$method = 'c_index';

		$load = $this->load->app($app);
		
		//If we loaded the user app or the default
		if($load)
		{
			//if the method we're trying to load exists, call it and pass the parameters
			if(method_exists($this->$app, $method))
				call_user_func_array(array($this->$app, $method), $params);
			else //if it doesn't, call index method and pass the non-existent method as an argument along with the rest of the params
				call_user_func_array(array($this->$app, 'c_index'), array_merge((array)$this->uri->segment(2), $params));
			
		}
		//could not find application with that name, falls back to see if we have a method called string in our default app
		else
		{
			$load = $this->load->app($this->config['default_app']);

			//If the default application loaded successfully
			if($load)
			{
				$method = $this->uri->segment(2);
				if(isset($method))
					$params = array_merge((array)$method, $params);
				
				$appname = $this->config['default_app'];
				
				if(method_exists($this->$appname, 'c_'.$app))
					call_user_func_array(array($this->$appname, 'c_'.$app), $params);
				else
					call_user_func_array(array($this->$appname, 'c_index'), $params);
				
			}
		}
	
	}
}
?>