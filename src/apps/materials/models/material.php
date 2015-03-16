<?php

class material extends model
{
  protected $xmlpath;
  public $info;
  
	function __construct(&$parent)
	{
		parent::__construct($parent);
	
		$this->table = 'materials';
		$this->idfield = 'name';
		$this->orderby_field = 'name';
		$this->config = $this->parent->config;
		$this->xmlpath = $this->parent->config['materialspath'];
	}
	
	public function load($name) {
	  $this->info = $this->get($name);
	  $blueprints = $this->db->query("select blueprints.name, blueprints.offertype, blueprints.rarity,
	  proto.dbid as dbid,
	  displayname.string as displayname
	  from blueprints 
	  left join proto on proto.name = blueprints.protounit
	  left join strings as displayname on displayname.stringid = proto.displaynameid
	  where materials like '%{$name}%'")->results(null, true);
	  
	  $this->info['blueprints'] = $blueprints;
	  
	  $designs_traits = $this->db->query("select designs.name, designs.rarity,
	  displayname.string as displayname
	  from designs
	  left join traits on traits.name = designs.output
	  left join strings as displayname on displayname.stringid = traits.displaynameid
	  where input like '%{$name}%' and displayname.string != ''")->results(null, true);
	  
	  $designs_consumables = $this->db->query("select designs.name, designs.rarity,
	  displayname.string as displayname
	  from designs
	  left join consumables on consumables.name = designs.output
	  left join strings as displayname on displayname.stringid = consumables.displaynameid
	  where input like '%{$name}%' and displayname.string != ''")->results(null, true);
	  
	  $this->info['blueprints'] = $blueprints;
	  $this->info['designs_traits'] = $designs_traits;
	  $this->info['designs_consumables'] = $designs_consumables;
	  
    return $this;
	}
	
	public function get($name) {
    return $this->db->query("select name, icon, offertype, rarity, cost, stacksize, cost,
    displayname.string as displayname, rollovertext.string as rollovertext
    from materials
    left join strings as displayname on displayname.stringid = materials.displaynameid
    left join strings as rollovertext on rollovertext.stringid = materials.rollovertextid
    where materials.name = '{$name}' limit 1")->results();
	}
	
	public function get_all() {
    return $this->db->query("select name, icon, offertype, rarity, cost, stacksize, cost, tradeable,
    displayname.string as displayname, rollovertext.string as rollovertext
    from materials
    left join strings as displayname on displayname.stringid = materials.displaynameid
    left join strings as rollovertext on rollovertext.stringid = materials.rollovertextid
    WHERE materials.name NOT LIKE 'FillerLoot_%' and materials.name NOT LIKE '!!LottoTicket%'
    ORDER BY displayname
    ")->results();
  }
  
  public function get_all_by_rarity($rarity) {
  	return $this->db->query("select name, icon, offertype, rarity, cost, stacksize, cost, tradeable,
      displayname.string as displayname, rollovertext.string as rollovertext
      from materials
      left join strings as displayname on displayname.stringid = materials.displaynameid
      left join strings as rollovertext on rollovertext.stringid = materials.rollovertextid
      WHERE materials.name NOT LIKE 'FillerLoot_%' AND materials.name NOT LIKE '!!LottoTicket%'
      AND materials.rarity = '$rarity'
      ORDER BY displayname
      ")->results();
  }
	
	/**
	* Method to update all database entries by re-reading the XML data files for traits
	* WARNING will overright any changes made on the database
	*/
	public function db_update()
	{
		
	    $XMLReader = new XMLReader();
	    $XMLReader->open($this->xmlpath);
	       
	    $this->delete_all();
	    
	    $fields = array(
	          'displaynameid',
	    	  'rollovertextid',
	          'icon',
	          'offertype',
		      'tradeable',
	    	  'destroyable',
	    	  'sellable',
	   		  'rarity',
	          'stacksize',
	          'itemlevel',
	          
	          'budgetcost'
		      );
          
	    while ($XMLReader->read()) 
	    {
		      if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "material")
		        continue;
		      
		      $doc = new DomDocument('1.0');
		      $doc->loadXML($XMLReader->readOuterXml());
		      
		      $item['name'] = $doc->documentElement->getAttribute('name');
		      //$trait['type'] = $doc->documentElement->getAttribute('type');
		      
		      // Most of the easy stuff
		      foreach ($doc->documentElement->childNodes as $node) {
		        if ($node->nodeType != 1)
		          continue;
		        
		        //if it's one of our fields up there
		        if (in_array($node->tagName, $fields))
		        {
		          $item[$node->tagName] = $node->nodeValue;
		        }
		        else if($node->tagName == "sellcostoverride")
		        {
		        	$n = $node->childNodes->item(1);
		        	$item['cost'] = $n->getAttribute('quantity');
		        }
		      }
		      
		      
		      // Fix icon filenames
		      $item = str_replace('\\', '/', $item);
		      
		      $item = str_replace('false', '0', $item);
		      $item = str_replace('true', '1', $item);
		      
		      $this->quicksave($item);
		      
		      unset($tech);
	    }
	}

	public function db_update_toc()
	{
		$this->db->query("DELETE FROM tableofcontents WHERE type='material'");
	
		$all = $this->get_all();
	
		foreach($all as $item)
		{
	
			$values = array(
	  					'dbid' => $item['name'],
	  					'keyword' => mysql_real_escape_string($item['displayname']),
	  					'searchtext' => mysql_real_escape_string($item['rollovertext']),
	  					'type' => 'material',
	  					'description' => mysql_real_escape_string($item['rollovertext']),
	  					'icon' => $item['icon']
				
			);
			$this->db->insert('tableofcontents', $values);
		}
	}
}

?>