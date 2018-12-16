<?php

class proto extends model
{
    protected $xmlpath, $units, $everything;
    public $info, $name, $description, $id;

    /**
     * Construct proto class, load configuration and construct parent (model)
     * @param unknown_type $parent
     */
    public function __construct(&$parent)
    {
        parent::__construct($parent);
        $this->table = 'proto';
        $this->idfield = 'id';
        $this->xmlpath = $this->parent->config['protopath'];
        $this->config = $this->parent->config;
    }

    /**
     * Overwrites MODEL::LOAD() - loads a Proto entry, sets the properties of this class to match the database info
     * @see model::load()
     */
    public function load($dbid)
    {

        $this->info = $this->db->query("select proto.*,
	    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
	    from proto
	    left join strings as displayname on displayname.stringid = proto.DisplayNameID
	    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
	    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID
	    where DBID = {$dbid} LIMIT 1")->results();

        if (isset($this->info['DBID'])) {
            $this->info['protoactions'] = $this->db->query('SELECT * FROM protoactions WHERE DBID = ' . $this->info['DBID'] . ' ')->results(null, true);
            $this->info['attacks'] = $this->attackTable();
        }
        //foreach($this->info as $key=>$info)
        //$this->$key = $info;

        return $this;
    }

    public function GetPaginated($civ = null, $type = null, $page = 1, $page_size = 100)
    {

        if ($civ === null) {
            $civ = '%';
        }
        if ($page < 1) {
            $page = 1;
        }

        $startAt = ($page - 1) * $page_size;

        $q = "select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
					    PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
					    CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
					    ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
					    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
					    FROM proto
					    left join strings as displayname on displayname.stringid = proto.DisplayNameID
					    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
					    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID
					    WHERE name LIKE '{$civ}\_%' AND name NOT LIKE '%\_e' AND name NOT LIKE '%\_r' AND name NOT LIKE '%\_u'";

        if ($type != null) {
            $q .= " AND name LIKE '%\_{$type}\_%'";
        }

        $q .= ' ORDER BY displayname';
        $q .= " LIMIT {$startAt},{$page_size}";

        $results = $this->db->query($q)->results(null, true);

        foreach ($results as $key => $unit) {
            $results[$key]['protoactions'] = $this->GetProtoActions($unit['DBID']);
            $results[$key]['attacks'] = $this->attackTable($results[$key]['protoactions']);
        }

        return $results;
    }

    public function GetProtoActions($unitId)
    {
        return $this->db->query('SELECT * FROM protoactions WHERE DBID = ' . $unitId)->results(null, true);
    }

    public function GetByName($name)
    {
        $query = $this->db->query("select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
			    PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
			    CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
			    ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
			    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
			    from proto
			    left join strings as displayname on displayname.stringid = proto.DisplayNameID
			    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
			    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID
			    where name LIKE '%$name%' limit 1");

        if ($query->success()) {
            $this->info = $query->results();
            $this->info['protoactions'] = $this->GetProtoActions($this->info['DBID']);
            return $this;
        } else {
            return null;
        }

    }

    public function get_total_count($civ = null, $type = null, $page_size = 100)
    {

        if ($civ === null) {
            $civ = '%';
        }

        $q = "SELECT COUNT(*)
					FROM proto
					WHERE name LIKE '{$civ}\_%' AND name NOT LIKE '%\_e' AND name NOT LIKE '%\_r' AND name NOT LIKE '%\_u'";

        if ($type != null) {
            $q .= " AND name LIKE '%\_{$type}\_%'";
        }

        return $this->db->query($q)->results()["COUNT(*)"];

    }

    public function GetAllByType($type)
    {
        $results = $this->db->query("select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
				    PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
				    CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
				    ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
				    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
				    FROM proto
				    left join strings as displayname on displayname.stringid = proto.DisplayNameID
				    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
				    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID
						WHERE name LIKE '%\_{$type}\_%'")->results();

        foreach ($results as $key => $unit) {
            $results[$key]['protoactions'] = $this->GetProtoActions($unit['DBID']);
            $results[$key]['attacks'] = $this->attackTable($results[$key]['protoactions']);
        }

        return $results;
    }

    public function GetAllByUTypes($utype)
    {
        $results = $this->db->query("select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
						    PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
						    CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
						    ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
						    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
						    FROM proto
						    left join strings as displayname on displayname.stringid = proto.DisplayNameID
						    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
						    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID
						    WHERE UnitTypes LIKE '%{$utype}%'")->results();
    }

    public function GetAllByCiv($civ, $type)
    {
        $q = "select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
					    PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
					    CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
					    ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
					    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
					    FROM proto
					    left join strings as displayname on displayname.stringid = proto.DisplayNameID
					    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
					    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID
					    WHERE name LIKE '{$civ}\_%' AND name NOT LIKE '%\_e' AND name NOT LIKE '%\_r' AND name NOT LIKE '%\_u'";

        if ($type != null) {
            $q .= " AND name LIKE '%\_{$type}\_%'";
        }

        $q .= ' ORDER BY displayname';

        $results = $this->db->query($q)->results(null, true);

        foreach ($results as $key => $unit) {
            $results[$key]['protoactions'] = $this->GetProtoActions($unit['DBID']);
            $results[$key]['attacks'] = $this->attackTable($results[$key]['protoactions']);
        }

        return $results;
    }

    private function attackTable($actions = null)
    {

        if ($actions == null) {
            $actions = $this->info['protoactions'];
        }

        if (!isset($actions)) {
            return false;
        }

        $attacks = array();

        foreach ($actions as $action) {
            if (strpos($action['Name'], 'Attack') == false) {
                continue;
            }

            $attack = array();
            $bonus = false;

            $attack['DBID'] = $action['DBID'];
            $attack['name'] = $action['Name'];
            $attack['type'] = $action['DamageType'];
            $attack['damage'] = $action['Damage'];
            $attack['bonuses'] = array();

            if ($attack['type'] == 'Ranged' || $attack['type'] == 'Siege') {
                $attack['range'] = $action['MaxRange'];
            }

            $attack['bonusvs'] = $action['DamageBonustype'];
            $attack['bonusvalue'] = $action['DamageBonus'];

            if (!isset($attack['damage'])) {
                $attacks[$attack['name']]['bonuses'][] = $attack;
                $bonus = true;
            }

            //Merging the arrays and ignoring empty values
            if (isset($attacks[$attack['name']])) {
                foreach ($attacks[$attack['name']] as $key => $value) {
                    if (!empty($value)) {
                        $attack[$key] = $attacks[$attack['name']][$key];
                    }

                }
            }

            if (!$bonus) {
                $attacks[$attack['name']] = $attack;
            }

        }

        /*echo '<pre>';
        print_r($attacks);
        echo '</pre>';*/

        return $attacks;

    }

    public function get_all()
    {
        $arr = $this->db->query("select id,name,DBID,MaxVelocity,MaxRunVelocity,MovementType,Icon,
			    PortraitIcon,InitialHitPoints,MaxHitPoints,LOS,PopulationCount,PopulationCapAddition,TrainPoints,
			    CostFood,CostGold,CostWood,CostStone,Bounty,Trait1,Trait2,Trait3,Trait4,AllowedAge,UnitTypes,
			    ArmorRanged,ArmorHand,ArmorCavalry,ArmorSiege,Flags,
			    displayname.string as DisplayName, rollovertext.string as RolloverText, shortrollovertext.string as ShortRolloverText
			    from proto
			    left join strings as displayname on displayname.stringid = proto.DisplayNameID
			    left join strings as rollovertext on rollovertext.stringid = proto.RolloverTextID
			    left join strings as shortrollovertext on shortrollovertext.stringid = proto.RolloverTextID")->results();

        foreach ($arr as $key => $unit) {
            $arr[$key]['protoactions'] = $this->GetProtoActions($unit['DBID']);
        }

        return $arr;
    }

    public function db_update_toc()
    {
        $this->db->query("DELETE FROM tableofcontents WHERE type='unit'");
        $all = $this->get_all();

        foreach ($all as $unit) {
            $values = array(
                'dbid' => $unit['DBID'],
                'keyword' => mysql_real_escape_string($unit['DisplayName']),
                'searchtext' => mysql_real_escape_string($unit['RolloverText']),
                'type' => 'unit',
                'description' => mysql_real_escape_string($unit['RolloverText']),
                'icon' => $unit['Icon'],
            );
            $this->db->insert('tableofcontents', $values);
        }
    }

    /**
     *
     * Updates all the database entries by re-reading XML data into the database
     * */
    public function db_update()
    {
        $XMLReader = new XMLReader();
        $XMLReader->open($this->xmlpath);

        $this->delete_all();
        $this->db->clear_table('protoactions');

        $unitfields = array('DBID',
            'DisplayNameID',
            'MaxVelocity',
            'MaxRunVelocity',
            'MovementType',
            'Icon',
            'PortraitIcon',
            'RolloverTextID',
            'ShortRolloverTextID',
            'InitialHitpoints',
            'MaxHitpoints',
            'LOS',
            'PopulationCount',
            'PopulationCapAddition',
            'TrainPoints',
            'Bounty',
            'Trait1',
            'Trait2',
            'Trait3',
            'Trait4',
            'AllowedAge');

        $protoactionfields = array('Name',
            'Damage',
            'ROF',
            'MinRange',
            'MaxRange',
            'DamageArea',
            'DamageFlags',
            'DamageType',
            'Projectile',
            'MaxHeight',
            'ImpactEffect',
            'Accuracy',
            'TrackRating',
            'Type');

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "Unit") {
                continue;
            }

            $doc = new DOMDocument('1.0');
            $x = $XMLReader->readOuterXml();

            $doc->loadXML($x);

            $unit['id'] = $doc->documentElement->getAttribute('id');
            $unit['name'] = $doc->documentElement->getAttribute('name');

            print_r($unit['name']);

            //save XML to file
            $dir = $this->parent->aoeo->config['exportpath'] . 'proto/';
            $filename = $unit['name'] . '.xml';

            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }

            file_put_contents($dir . $filename, $x);

            // Most of the good stuff
            foreach ($doc->documentElement->childNodes as $node) {
                if ($node->nodeType != 1) {
                    continue;
                }

                if (in_array($node->tagName, $unitfields)) {
                    $unit[$node->tagName] = $node->nodeValue;
                }

            }

            // Unit Types
            $UnitTypes = $doc->documentElement->getElementsByTagName('UnitType');
            if ($UnitTypes->length > 0) {
                $unit['UnitTypes'] = '';
                foreach ($UnitTypes as $UnitType) {
                    $unit['UnitTypes'] .= $UnitType->nodeValue . ',';
                }
                $unit['UnitTypes'] = substr($unit['UnitTypes'], 0, -1);
            }

            // Flags
            $Flags = $doc->documentElement->getElementsByTagName('Flag');
            if ($Flags->length > 0) {
                $unit['Flags'] = '';
                foreach ($Flags as $Flag) {
                    $unit['Flags'] .= $Flag->nodeValue . ',';
                }
                $unit['Flags'] = substr($unit['Flags'], 0, -1);
            }

            // Cost
            $Costs = $doc->documentElement->getElementsByTagName('Cost');
            if ($Costs->length > 0) {
                foreach ($Costs as $Cost) {
                    $resource = $Cost->getAttribute('resourcetype');
                    switch ($resource) {
                        case 'Food':
                            $unit['CostFood'] = $Cost->nodeValue;
                            break;
                        case 'Wood':
                            $unit['CostWood'] = $Cost->nodeValue;
                            break;
                        case 'Gold':
                            $unit['CostGold'] = $Cost->nodeValue;
                            break;
                        case 'stone':
                            $unit['CostStone'] = $Cost->nodeValue;
                            break;
                    }
                }
            }

            // Armor
            $Armors = $doc->documentElement->getElementsByTagName('Armor');
            if ($Armors->length > 0) {
                foreach ($Armors as $Armor) {
                    $type = $Armor->getAttribute('type');
                    switch ($type) {
                        case 'Ranged':
                            $unit['ArmorRanged'] = $Armor->getAttribute('value');
                            break;
                        case 'Hand':
                            $unit['ArmorHand'] = $Armor->getAttribute('value');
                            break;
                        case 'Cavalry':
                            $unit['ArmorCavalry'] = $Armor->getAttribute('value');
                            break;
                        case 'Siege':
                            $unit['ArmorSiege'] = $Armor->getAttribute('value');
                            break;
                    }
                }
            }

            // Actions
            $ProtoActions = $doc->documentElement->getElementsByTagName('ProtoAction');
            if ($ProtoActions->length > 0) {
                foreach ($ProtoActions as $ProtoActionElement) {
                    $ProtoAction['DBID'] = $unit['DBID'];

                    // Basic stuff
                    foreach ($ProtoActionElement->childNodes as $ProtoActionNode) {
                        if ($ProtoActionNode->nodeType != 1) {
                            continue;
                        }

                        if (in_array($ProtoActionNode->tagName, $protoactionfields)) {
                            $ProtoAction[$ProtoActionNode->tagName] = $ProtoActionNode->nodeValue;
                        }
                        // DamageBonus
                        elseif ($ProtoActionNode->tagName == 'DamageBonus') {
                            $ProtoAction['DamageBonustype'] = $ProtoActionNode->getAttribute('type');
                            $ProtoAction['DamageBonus'] = $ProtoActionNode->nodeValue;
                        }
                    }

                    // Rate
                    $Rates = $ProtoActionElement->getElementsByTagName('Rate');
                    if ($Rates->length > 0) {
                        $ProtoAction['Ratetype'] = '';
                        $ProtoAction['Rate'] = '';
                        foreach ($Rates as $Rate) {
                            $ProtoAction['Ratetype'] .= $Rate->getAttribute('type') . ',';
                            $ProtoAction['Rate'] .= $Rate->nodeValue . ',';
                        }
                        $ProtoAction['Ratetype'] = substr($ProtoAction['Ratetype'], 0, -1);
                        $ProtoAction['Rate'] = substr($ProtoAction['Rate'], 0, -1);
                    }

                    $this->db->insert('protoactions', $ProtoAction);
                    //print_r($ProtoAction);

                    unset($ProtoAction);
                }
            }

            //$Armors = $doc->documentElement->getElementsByTagName('Armor');

            // Fix icon filenames
            $unit = str_replace('\\', '/', $unit);

            //print_r($unit);
            $this->quicksave($unit);

            unset($unit);
        }

    }

}
