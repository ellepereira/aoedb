<?php
class easydb
{
	protected $db, $query_array;
	protected $querystring;
	
	function __construct(&$db)
	{
		$this->db = $db;

		$this->default_query = array('select'=>'*','update'=>'','insert'=>'','delete'=>'',
		'from'=>'','left_join'=>'','into'=>'','where'=>'1=1','order_by'=>'', 'limit'=>'');
		$this->query_array = $this->default_query;
	}
	
	public function __call($name, $args)
	{
		if(array_key_exists($name, $this->query_array))
		{
			$this->query_array[$name] = $args[0];
		}
		
		else
		{
			if(method_exists($this->db, $name))
				return $this->db->$name();
		}

		return $this;
	}
	
	public function get()
	{
		$r = $this->query_array;
		$q = 'SELECT '.$r['select'].' FROM '.$r['from']. ' WHERE '. $r['where'];
		
		if(strlen($r['limit']) > 0)
			$q.= ' LIMIT '.$r['limit'];
			
		if(strlen($r['order_by']) > 0)
			$q.= ' ORDER BY '.$r['order_by'];
		
		$this->db->query($q);
		
		$this->query_array = $this->default_query;
		
		return $this;
	}
}
?>