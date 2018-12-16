<?php
class aoeomodel extends component
{
    public $children;

    protected $fields;

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->db = $this->parent->db;
        //Our database fields

        /**CONFIGURATION*/
        $this->table = 'proto';
        $this->idfield = 'dbid';
        $this->orderby = 'name';
        $this->where = '';
        $this->mainXMLElementName = 'Unit';

        $this->fields = array('DBID', 'name', 'MaxVelocity', 'MaxRunVelocity', 'MovementType', 'Icon', 'PortraitIcon', 'InitialHitPoints'
            , 'MaxHitPoints', 'LOS', 'PopulationCount', 'PopulationCapAddition', 'TrainPoints', 'CostFood', 'CostWood', 'CostGold',
            'CostStone', 'Bounty', 'Trait1', 'Trait2', 'Trait3', 'Trait4', 'Trait5', 'AllowedAge', 'UnitTypes', 'ArmorRanged', 'ArmorHand', 'ArmorCavalry', 'ArmorSiege',
            'Flags');

        //Add on fields may not be part of this model's XML, for example, materials may be an add-on field of blueprint
        $this->add_on_fields = array('protoactions' => 'DBID');
        $this->string_fields = array('DisplayNameID' => 'DisplayName', 'RolloverTextID' => 'RolloverText', 'ShortRolloverTextID' => 'ShortRolloverText');

        //This is the definition of add_on_fields that ARE part of this XML
        $this->children =
        array('protoactions' => array('Name', 'Damage', 'ROF', 'MinRange', 'MaxRange', 'DamageArea',
            'DamageFlags', 'DamageType', 'Projectile', 'MaxHeight', 'ImpactEffect', 'Accuracy', 'TrackRating', 'Type'));

        /**END CONFIGURATION*/

        /**CONFIG 2.0**/

        $this->nconfig =
        array(
            'table' => 'proto',
            'idfield' => 'dbid',
            'orderby' => 'name',
            'mainEl' => 'Unit',
            'fields' //XML fields - means it's an attribute
            => array('DBID', '-name', 'MaxVelocity', 'MaxRunVelocity', 'MovementType', 'Icon', 'PortraitIcon', 'InitialHitPoints'
                , 'MaxHitPoints', 'LOS', 'PopulationCount', 'PopulationCapAddition', 'TrainPoints', 'CostFood', 'CostWood', 'CostGold',
                'CostStone', 'Bounty', 'Trait1', 'Trait2', 'Trait3', 'Trait4', 'Trait5', 'AllowedAge', '*UnitTypes', 'ArmorRanged', 'ArmorHand',
                'ArmorCavalry', 'ArmorSiege', '*Flags', '*protoactions'),
        );

        /**/

    }

    protected function read_element($xmlEl)
    {
        $doc->loadXML($xmlEl);
        $de = $doc->documentElement;

        //Are any of the fields we're looking for attributes?
        if ($de->hasAttributes) {
            foreach ($de->attributes as $attr) {
                if (in_array($attr->nodeName, $this->fields)) {
                    $out[$attr->nodeName] = $attr->nodeValue;
                }

            }
        }

        // Now let us check if any of our fields are one of our children nodes
        foreach ($doc->documentElement->childNodes as $child) {
            if ($child->nodeType != 1) {
                continue;
            }

            if (in_array($node->tagName, $this->fields)) {
                $out[$child->tagName] = $node->nodeValue;
            }

        }
    }

    public function read_xml($file)
    {
        $XMLReader = new XMLReader();
        $XMLReader->open($file);
        $output = array();

        //clear our table
        $this->delete_all();

        //Also clear children fields
        foreach ($this->children as $fname => $fvalue) {
            $this->db->clear_table($fname);
        }

        //Start our reading
        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != $this->mainXMLElementName) {
                continue;
            }

            //We switch to DOM, don't remember why we do this but yup.
            $doc = new DOMDocument('1.0');
            $x = $XMLReader->readOuterXml();
            $output = array(); //our output array that is going into the db

            $output[] = $this->read_element($x);
        }

        return $output;
    }

    /**
     * Creates a new query using default configuration
     * @see model::get()
     */
    public function get()
    {
        $query = array(
            'fields' => $this->query_create_fields(),
            'addon' => $this->add_on_fields,
            'strings' => $this->string_fields,
            'orderby' => $this->orderby,
            'where' => $this->where,
        );

        $this->query = $query;
        return $this;
    }

    public function delete_all()
    {
        $this->db->clear_fields($this->table);
    }

    private function query_create_fields($arr)
    {
        $r = array();
        foreach ($this->query['fields'] as $field) {
            $r[$field] = '';
        }

        return $r;
    }

    public function disable_addons()
    {
        $this->query['addon'] = array();
    }

    public function hide($field)
    {
        if (in_array($field, $this->query['fields'])) {
            unset($this->query['fields'][$field]);
        } else if (in_array($field, $this->query['addon'])) {
            unset($this->query['addon'][$field]);
        } else if (in_array($field, $this->query['strings'])) {
            unset($this->query['strings'][$field]);
        }

        return $this;
    }

    public function results()
    {
        $joinstring = '';
        $string = 'SELECT ';
        $fields = implode(',', $this->query['fields']);

        $string .= $fields;

        foreach ($this->query['strings'] as $stringfield => $stringname) {
            $string .= ", {$stringname}.string AS {$stringname}";

            $lowername = strtolower($stringname);
            $joinstring .= "LEFT JOIN strings AS {$lowername} ON {$lowername}.stringid = {$this->table}.{$stringfield} \n";
        }

        $string = "$string FROM {$this->table} \n $joinstring";

        if (!empty($this->query['where'])) {
            $string .= " WHERE {$this->query['where']}";
        }

        $results = $this->db->query($string)->results();

        $squery = '';

        foreach ($results as $k => $result) {
            foreach ($this->query['addon'] as $addon => $joinby) {
                $results[$k][$addon] = $this->db->query("SELECT * FROM {$addon} WHERE {$joinby} = '{$result[$joinby]}'")->results();
            }
        }

        return $results;
    }

    public function __call($name, $args)
    {

    }

}
