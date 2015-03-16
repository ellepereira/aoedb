<?php
$config = array(
	'exportpath' => $_SERVER['DOCUMENT_ROOT'].'/xmlfiles/',
	'datapath' => 'c:\\aoeofiles\\data\\data\\',
	'exportxml' => 
		array(  
				array('file' => 'achievements.xml', 'mainelement'=>'achievement', 'nameelement' => 'internalname'), 
				array('file' => 'advisors.xml', 'mainelement'=>'advisor', 'nameattribute' => 'name'),
				array('file' => 'craftschools.xml', 'mainelement'=>'school', 'nameelement' => 'tag'),
				array('file' => 'EconBlueprints.xml', 'mainelement'=>'blueprint', 'nameattribute' => 'name'),
				array('file' => 'EconConsumables.xml', 'mainelement' => 'consumable', 'nameattribute' => 'name'),
				array('file' => 'EconDesigns.xml', 'mainelement' => 'econdesign', 'nameattribute' => 'name'),
				array('file' => 'econmaterials.xml', 'mainelement' => 'material', 'nameattribute' => 'name'),
				array('file' => 'EconVendors.xml', 'mainelement' => 'vendor', 'nameattribute' => 'name'),
				array('file' => 'equipment.xml', 'mainelement' => 'equipment', 'nameattribute' => 'id'),
				array('file' => 'traits.xml', 'mainelement' => 'trait', 'nameattribute' => 'name')
				)
);