<?php
class model extends component
{
	protected $db;
	protected $table, $idfield, $info;
	public $orderby_field;
	public $loader;
	
	function __construct(&$parent)
	{
		parent::__construct($parent);
		$this->db = &$this->parent->db;
		$this->load = &$this->parent->load;
		$this->config = &$this->parent->config;
	}
	
	function load($id)
	{
		$this->info = $this->get($id);	
		return $this;
	}

	function get($id)
	{
		if(!$this->idfield || !$this->table)
			return null;
			
		return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->idfield}={$id} LIMIT 1")->results();
	}

	function delete($id = null)
	{
		if(!$this->idfield || !$this->table)
			return null;
		
		$idfield = $this->idfield;
			
		if($id == null)
			$id = $this->$idfield;
		
		if($id == null)
			return null;
			
		$r = $this->db->query("DELETE FROM {$this->table} WHERE {$this->idfield}={$id}");
		
		
		return $r;
	}
	
	function delete_all()
	{
		if(isset($this->table))
			return $this->db->clear_table($this->table);
		
		return null;
	}
	
	function get_all()
	{
		if(!$this->table)
			return null;
			
		if($this->orderby_field)
			$order = "ORDER BY {$this->orderby_field} ASC";
		
		return $this->db->query("SELECT * FROM {$this->table} {$order}")->results();
	}

	function quicksave($data, $id = null)
	{	
		if(!$this->idfield || !$this->table)
			return null;
			
		if($id == null)
		{
			$this->db->insert($this->table, $data);
		}
		else
		{
			$this->db->update($this->table, $data, "`{$this->idfield}` = '{$id}'");
		}
			
		return $this->db->success();
	}
	
	function set($field, $value, $id = null)
	{
		if(!$this->idfield || !$this->table)
			return null;
		
		$idfield = $this->idfield;
			
		if($id == null)
			@$id = $this->$idfield;
		
		if($id == null)
			return null;
		
		return $this->db->query("UPDATE {$this->table} SET {$field}='{$value}' WHERE {$this->idfield}={$id}");
	}
	
	function __get($name)
	{
		if(isset($this->info[$name]))
			return $this->info[$name];
		else
			return null;
	}
	
	function __set($name, $value)
	{
		if(!$this->idfield || !$this->table)
			return null;
			
		if(isset($this->info)){
			//$this->set($name, $value);
		}
		else{
			$this->$name = $value;}
	}
}

/**end of file*/