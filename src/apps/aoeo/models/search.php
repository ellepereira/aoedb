<?php
  
class search extends model
{
  protected $xmlpath;
  
	function __construct(&$parent, $level = null)
	{
		parent::__construct($parent);
	
		$this->table = 'tableofcontents';
		$this->idfield = 'tcid';
		$this->orderby_field = 'tcid';
	}
	
	public function get_all()
	{
		$arr = $this->db->query("SELECT * FROM tableofcontents")->results();
		$arr = $this->organize_by_type($arr);
		
		return $arr;
	}
	
	/**
	 * Searches on our table of content entries for a string
	 * If only one is found, returns under a ONLY associate array so it can
	 * be easy to figure out perfect matches
	 * @param string $s
	 */
	public function search($s, $type = null)
	{	
		if(!$type)
			$r = $this->db->query("SELECT * FROM tableofcontents WHERE keyword LIKE '%{$s}%' OR searchtext LIKE '%{$s}%'")->results(null, true);
		else
			$r = $this->db->query("SELECT * FROM tableofcontents WHERE type='$type' AND (keyword LIKE '%{$s}%' OR searchtext LIKE '%{$s}%')")->results(null, true);
		
		if(count($r) == 1)
		{
			$r['ONLY'] = $r[0];
			return $r;
		}
		
		$r = $this->organize_by_type($r);
		
		return $r;
	}
	
	protected function organize_by_type($arr)
	{
		if(count($arr) < 1)
			return null;
		
		$out = array();
		
		foreach($arr as $k=>$v)
		{
			$out[$v['type']][] = $v;
		}
		
		return $out;
	}

}