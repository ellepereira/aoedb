<?php
class mysql implements idatabase
{
	public $connection;
	protected $lquery, $lquery_type;
	
	public function connect($p_connect = true)
	{
		require 'config/mysql.php';
		
		if($p_connect == true)
			$this->connection = mysql_pconnect($db['host'], $db['user'], $db['pass']);
		else
			$this->connection = mysql_connect($db['host'], $db['user'], $db['pass']);
			
		mysql_select_db($db['dbname']);
				
		return $this;
	}
	
	public function query($str)
	{
		if(!isset($this->connection))
			$this->connect();
		
		$t = explode(' ', $str);
	
		$this->lquery_type = strtolower($t[0]);
		
		$this->lquery = @mysql_query($str, $this->connection);
		
		if(!$this->lquery)
		{
			throw new AppException(mysql_error().$str);
		}
		
		return $this;
	}
	
	public function get_connection()
	{
		return $this->connection;
	}
	
	public function get_num_rows($query = null)
	{
		if($query != null)
			$this->query($query);
		
		return mysql_num_rows($this->lquery);
	}
	
	public function get_last_query()
	{
		return $this->lquery;
	}
	
	public function insert($into, $values)
	{
		$qFields = '';
		$qValues = '';
		$first = true;
			
		foreach($values as $key=>$value)
		{
			if($first)
			{
				$first = false;
			}
			else
			{
				$qFields .= ',';
				$qValues .= ',';
			}
			
				
			$qFields .= "`$key`";
			$qValues .= "'$value'";
		}
		//echo "INSERT INTO $into($qFields) VALUES($qValues)";
		return $this->query("INSERT INTO $into($qFields) VALUES($qValues)");
	}
	
	public function get_last_insert_id()
	{
		return mysql_insert_id();
	}
	
	public function results($query = null, $force_multiple = false)
	{
		if($query)
			$this->query($query);
			
		$results = array();
			
		while($ar = @mysql_fetch_assoc($this->lquery))
		{
			$results[] = $ar;
		}
		
		if(count($results) == 1 && $force_multiple == false)
			$results = $results[0];
			
		return $results;
	}
	
	public function success($query= null)
	{
		if($query)
			$this->query($query);
			
		switch($this->lquery_type)
		{
			//INSERT, UPDATE, REPLACE or DELETE
			case 'insert':
			case 'update':
			case 'replace':
			case 'delete':
				return (mysql_affected_rows() > 0);
				break;
			
			case 'select':
			case 'show':
				if(!is_resource($this->lquery))
					return false;
				return (mysql_num_rows($this->lquery) > 0);
				break;
			
			default:
				return (mysql_num_rows($this->lquery) > 0 || mysql_affected_rows() > 0);
				break;
		}
	}

	public function update($table, $value, $where = '1=1', $limit = 1)
	{
		if(is_array($value))
			$value = $this->specialglue(',', $value);
			
		if(is_array($where))
			$where = implode(' AND ', $where);	
	
		return $this->query("UPDATE $table SET $value WHERE $where LIMIT $limit;");
	}
	
	public function clear_table($table)
	{
		return $this->query('TRUNCATE TABLE `'.$table.'`');
	}
	
	private function specialglue($glue, $pieces)
	{
		$first = true;
		$result = null;
		
		foreach($pieces as $key=>$value)
		{
			if($first)
				$first = false;
			else
				$result .= $glue;
			
				
			$result .= "$key = '$value'";
		}
		
		return $result;
	}

	public function clean_string($string)
	{	
		return mysql_real_escape_string($string);
	}
	
}