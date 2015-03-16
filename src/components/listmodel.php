<?php
class listmodel extends component
{
	protected $db;
	protected $table, $idfield, $info;
	public $orderby_field;
	
	function __construct(&$parent)
	{
		parent::__construct($parent);
		$this->db = $this->parent->db;
	}
	

	function get($id)
	{
		if(!$this->idfield || !$this->table)
			return null;
			
		return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->idfield}={$id} LIMIT 1")->results();
	}

	function delete($id)
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
			$id = $this->$idfield;
		
		if($id == null)
			return null;
		
		return $this->db->query("UPDATE {$this->table} SET {$field}='{$value}' WHERE {$this->idfield}={$id}");
	}
	
	function __set($name, $value)
	{
		if(!$this->idfield || !$this->table)
			return null;
			
		if(isset($this->info))
			$this->set($name, $value);
		else
			$this->$name = $value;
	}
}