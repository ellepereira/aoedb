<?php
/**
 * Parent class to all Applications
 * 
 * @author lspereira
 *
 */
class app extends component
{
	public $system, $config;
	
	function __construct(&$parent = null)
	{
		parent::__construct($parent);
		
		/**
		 * All applications need the loader
		 * @var unknown_type
		 */
		$this->load = new loader($this);
		
		//$this->load->config('default');

		/**
		 * Abrir o config dessa app, se existir.
		 * Open our config file, if we have one
		 */
		$this->load->config(get_class($this));
		
		if(isset($this->config['load_alt_config']))
			$this->load->config($this->config['load_alt_config']);
		
		
		/**
		 * Input cleaning library
		 */
		$this->load->lib('input');
		
		/**
		 * URL parsing library
		 */
		$this->load->lib('uri');
		
		/**
		 * Inicializa a base de dados
		 * Initialize db
		 */
		$this->init_db();
		
		/**
		 * Inicializa o nosso logger
		 */
		//$this->load->app('log');
		//$this->log->app = get_class($this);
		
		//Alias
		$this->system = &$this->parent;
		
	}
	
	/**
	 * Check if we use the database and initialize it
	 * Checa se usamos a base de dados e inicializa se sim
	 * @return null
	 */
	private function init_db()
	{
		/**
		 * If we've set that we want to use the database, initialize it.
		 * Or we may use our parent's
		 * 
		 * Inicializa a base de dados se esse app estiver configurado pra usar a base
		 * de dados. Ou usa do nosso parent
		 */
		
		if (isset($this->parent->db))
		{
			$this->db = $this->parent->db;
		}
		
		else
		{
			$this->load->db('mysql');
		}
		
		
	}
	
	/**
	 * Implementation of __invoke() from PHP 5.3
	 * @param $name
	 * @param $args
	 * @return unknown_type
	 */
	public function __call($name, $args)
	{
		if(is_object($this->$name))
				call_user_func_array(array($this->$name, '__invoke'), $args);
		else
			throw new AppException('Method not found! '. $name);
	}
	
	/**
	 * 
	 * Shortcut for $this->load->view($name, $view)
	 * @param unknown_type $data
	 */
	public function show($name, $data = null)
	{
		$this->load->view($name, $data);
	}
	
	
	/**
	 * Get a property
	 * 
	 * If member doesn't exist, it attempts to call
	 * a method having the same name, without arguments
	 * 
	 * @param $name
	 * @return unknown_type
	 */
	public function __get($name)
	{
		if(method_exists($this, $name))
			return $this->$name();
	}
	
}

/**end of file*/