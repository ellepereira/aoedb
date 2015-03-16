<?php
/**
 * URI Class
 * Cleans URI request
 * Limpa o URI requesitado
 * @author lspereira
 *
 */
class uri extends component
{
	/**
	 * Array of segments divided by '/'
	 * @var unknown_type
	 */
	protected $segments;
	public $uristring;
	
	public function __construct(&$parent = null)
	{
		parent::__construct($parent);
		//$p = pathinfo($_SERVER['PHP_SELF']);
		//$s = str_replace($p['dirname'], '', $_SERVER['REQUEST_URI']);
		//$s = str_replace('//', '', $s);
		$s = $_SERVER['REQUEST_URI'];
		$this->uristring = $s;
		$this->segments = explode('/', $s);
		//print_r($this->segments);
		//print_r($_SERVER);
	}
	
	/**
	 * Returns the requested segment
	 * @param $n index
	 * @return unknown_type
	 */
	public function segment($n)
	{
		if(isset($this->segments[$n]))
			return $this->segments[$n];
		else
			return null;
	}
	
	/**
	 * Returns the name of the requested method
	 * Retorna o nome do metodo requesitado
	 * @return unknown_type
	 */
	public function method()
	{
		//shorthand
		$c = $this->parent->config;	
		$method = $c['method_prefix'].$c['index_method'];

		if(!empty($this->segments[2]))
			$method = $c['method_prefix'].$this->segments[2];	
		
		return $method;
	}
	
	/**
	 * Returns the name of the requested Application
	 * Retorna o nome do applicativo requesitado
	 * @return unknown_type
	 */
	public function app()
	{
		$c = $this->parent->config;
		$app = null;
		
		if(isset($this->segments[1]) && !empty($this->segments[1]))
			$app = $this->segments[1];
		
		return $app;
	}

	
	/**
	 * Returns the passed URI parameters
	 * Retorno os parametros passados no URI
	 * @return unknown_type
	 */
	public function params()
	{
		$params = null;
		$return = array();
		
		if(isset($this->segments[3]))
			$params = array_slice($this->segments, 3);
		
		if(isset($params))
			foreach($params as $param)
				$return[] = $param;
		
		return $return;
	}
}