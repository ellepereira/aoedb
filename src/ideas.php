<?php
/**
 * 
 * MAIN class
 * Load all applications centrally
 * Allow interactions between all applications
 * Decide which application to call and what controller to call
 * Provide helpers
 * 
 * URL: INTRANET.cba/index.php ->
 * 		instantiates a new main.php
 * 		__FILE__
 * 
 * URL: INTRANET.cba/index/APP/CONTROLLER/VARS
 * 
 * 
 * This class is called like this:
 * $app->c_index() <-- calls our controller's index
 * 		__method call, analyzes the URL to check which controller we're currently using.	
 * $app->index() <-- calls our index method, not our controller's
 * $app->o_index <-- calls _call method on object index
 * 	(if $app->index() is not found, we try $app->o_index)
 * 
 * main app controller's base methods:
 * $base->input(..); <-- handles input
 * $base->db -> initializes and handles DB connection
 * $base->load_lib(..) -- loads a library (into a var $l_LIBNAME)
 * $base->config(..) -- gets or sets a configuration directive.
 * 
 * globals:
 * $apps <-- handler of all applications in our system
 * 	$apps->googlebot->etc(..) <-- calls etc method on googlebot app
 * 
 * 
 * googlebot app:
 * 	main:
 * 		$googlebot::__construct()
 * 				$base->__construct()
 * 
 * $app->c_vertodos
 */


/**
*
* $arr = $this->db->query("select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
from proto
left join strings as displayname on displayname.stringid = proto.DisplayNameID
left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID")->results();
*
*
* Example queries:
*
* $this->units->get()->hide('name', 'LOS')->strings('rollovertextid', 'displaynameid')->results();
* ^ would fetch all units, not have their name and LOS on the results and automatically fetch strings for rollovertextid and displaynameid
*
* $this->units->get()->notif('name == null')->ordered()->results();
* ^ gets all units where name isn't null and orders then by default ordering config
*
* $this->units->get()->where('type', 'AbstractCavalry')->results();
*
* $this->units->getwhere('type', 'AbstractInfantry')->results()->as_array();
* ^ returns the infantry list as array
*
* $this->units->get()->hide('protoactions');
* ^ gets all units but without query for protoactions
*
* generating the query:
*
*
* get() returns a copy of this object containing certain query information
* 	this information is a default query set by the class, in this example, units.
*
* get() runs through the pre-configured list of units fields - calls any special method to add to the query -- for example on units, protoactions is a separate table
*
* so when get() runs through the list of fields, it gets to protactions and calls $this->cb_protoactions (cb stands for callback) and uses that to get the value for the unit's protoactions
* field.
*
*
*
* 	$q_default_where = '*';
*  $q_joins = 'the string joins';
*  $q_orderby = 'dbid';
*
*
*
*
* extra methods
*
*  protected $this->endquery($query, $field);
*   ^^ adds a query to be executed right after the main query is executed - would be used for example: instead of doing a query every time to fetch each unit protoattack, do one major query
*   here for all the protoactions by calling $this->endquery('SELECT * FROM protoactions', 'protoaction') <-- this automatically fetches the results and adds them to each unit's
*   protoaction variable with matching dbids.
*/


?>