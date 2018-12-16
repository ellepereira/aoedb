<?php

class blueprint extends model
{
  protected $xmlpath;
  
  public $info;
  
	function __construct(&$parent)
	{
		parent::__construct($parent);
	
		$this->table = 'blueprints';
		$this->idfield = 'name';
		$this->orderby_field = 'name';
		$this->config = $this->parent->config;
		$this->xmlpath = $this->parent->config['blueprintspath'];
	}
	
	public function load($dbid) {
    $this->info = $this->get($dbid);
    
    if (strlen($this->info['materials']) > 0) {
      $materials = explode(',', $this->info['materials']);
      unset($this->info['materials']);
      $wherestring = 'WHERE ';
      foreach ($materials as $material) {
        $matstemp = explode('=', $material);
        $matcounts[$matstemp[0]] = $matstemp[1];
        $wherestring .= "materials.name = '{$matstemp[0]}' OR ";
      }
      $wherestring = substr($wherestring, 0, -4);
      
      $query = 'select name, icon, rarity, displayname.string as displayname from materials left join strings as displayname on displayname.stringid = materials.displaynameid ' . $wherestring;
      
      $mats = $this->db->query($query)->results(null, true);
      
      foreach ($mats as $mat) {
        $this->info['materials'][] = array('name' => $mat['name'], 'displayname' => $mat['displayname'], 'icon' => $mat['icon'], 'count' => $matcounts[$mat['name']], 'rarity' => $mat['rarity']);
      }
    }
    else {
      unset($this->info['materials']);
    }
    
    return $this;
  }
  
  public function get($dbid)
  {
  	return $this->db->query("select blueprints.name,blueprints.cost,blueprints.materials,blueprints.icon,blueprints.offertype,blueprints.rarity,blueprints.protounit, blueprints.tradeable,
  	    displayname.string as displayname, rollovertext.string as rollovertext,
  	    proto.DBID as DBID, proto.PortraitIcon as PortraitIcon
  	    from blueprints
  	    left join proto on proto.name = blueprints.protounit
  	    left join strings as displayname on displayname.stringid = proto.DisplayNameID
  	    left join strings as rollovertext on rollovertext.stringid = blueprints.rollovertextid
  	    where proto.dbid = '{$dbid}' LIMIT 1")->results();
  }
  
  public function get_all()
  {
  	return $this->db->query("select blueprints.name,blueprints.cost,blueprints.materials,blueprints.icon,blueprints.offertype,blueprints.rarity,blueprints.protounit, tradeable,
  	    displayname.string as displayname, rollovertext.string as rollovertext,
  	    proto.DBID as DBID, proto.PortraitIcon as PortraitIcon
  	    from blueprints
  	    left join proto on proto.name = blueprints.protounit
  	    left join strings as displayname on displayname.stringid = proto.DisplayNameID
  	    left join strings as rollovertext on rollovertext.stringid = blueprints.rollovertextid
  	    ORDER BY displayname
    	   ")->results();
  }
  
  public function get_all_by_rarity($rarity)
  {
  	return $this->db->query("select blueprints.name,blueprints.cost,blueprints.materials,blueprints.icon,blueprints.offertype,blueprints.rarity,blueprints.protounit, tradeable,
    	    displayname.string as displayname, rollovertext.string as rollovertext,
    	    proto.DBID as DBID, proto.PortraitIcon as PortraitIcon
    	    from blueprints
    	    left join proto on proto.name = blueprints.protounit
    	    left join strings as displayname on displayname.stringid = proto.DisplayNameID
    	    left join strings as rollovertext on rollovertext.stringid = blueprints.rollovertextid
    	    WHERE rarity = '{$rarity}'
    	    ORDER BY displayname
      	   ")->results();
  }
  
  
  public function db_update_toc()
  {
  	$this->db->query("DELETE FROM tableofcontents WHERE type='blueprint'");
  
  	$all = $this->get_all();
  
  	foreach($all as $item)
  	{

  		$values = array(
  					'dbid' => $item['DBID'],
  					'keyword' => mysql_real_escape_string($item['displayname']),
  					'searchtext' => mysql_real_escape_string($item['rollovertext']),
  					'type' => 'blueprint',
  					'description' => mysql_real_escape_string($item['rollovertext']),
  					'icon' => $item['icon']
  			
  		);
  		$this->db->insert('tableofcontents', $values);
  	}
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
	          
	          'protounit'
		      );
          
	    while ($XMLReader->read()) 
	    {
		      if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "blueprint")
		        continue;
		      
		      $doc = new DomDocument('1.0');
		      $doc->loadXML($XMLReader->readOuterXml());
		      
		      $item['name'] = $doc->documentElement->getAttribute('name');
		      //$trait['type'] = $doc->documentElement->getAttribute('type');
		      
		      // Most of the easy stuff
		      foreach ($doc->documentElement->childNodes as $node) 
		      {
		        if ($node->nodeType != 1)
		          continue;
		        
		        //if it's one of our fields up there
		        if (in_array($node->tagName, $fields))
		        {
		          $item[$node->tagName] = $node->nodeValue;
		        }
		        
		        else if($node->tagName == "cost")
		        {
		        	foreach($node->childNodes as $n )
		        	{
		        		if ($n->nodeType == XMLReader::TEXT)
		       				 continue;
		        		
			        	if(isset($item['materials']))
			        		$item['materials'] .= ',';
			        	else
			        		$item['materials'] = '';
			        	
			        	$item['materials'] .= "{$n->nodeValue}={$n->getAttribute('quantity')}";
		        	}
		        }
		        
		        else if($node->tagName == "sellcostoverride")
		        {
		        	$n = $node->getElementsbyTagName('capitalresource')->item(0);
		        	$item['cost'] = $n->getAttribute('quantity');
		        }
		      }
		      
		      
		      // Fix icon filenames
		      $item = str_replace('\\', '/', $item);

		      $this->quicksave($item);
		      
		      unset($item);
	    }
	}
}

?>