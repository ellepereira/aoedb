<?php

class advisor extends model
{
    protected $xmlpath;

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->table = 'advisors';
        $this->idfield = 'ad_id';
        $this->orderby_field = 'ad_id';
        $this->config = $this->parent->config;
        $this->xmlpath = $this->parent->config['advisorspath'];
    }

    public function get($name)
    {
        $arr = $this->db->query("select advisors.name, advisors.rarity, advisors.icon,
			advisors.age, advisors.minlevel, advisors.itemlevel, advisors.cost,
				        displayname.string as displayname,
				        displaydescription.string as displaydescription
				        FROM  advisors
				        LEFT JOIN strings AS displayname on displayname.stringid = advisors.displaynameid
				        LEFT JOIN strings AS displaydescription on displaydescription.stringid = advisors.displaydescriptionid
				        WHERE advisors.name = '{$name}'")->results();

        return $arr;
    }

    public function db_update_toc()
    {
        $this->db->query("DELETE FROM tableofcontents WHERE type='advisor'");

        $all = $this->get_all();

        foreach ($all as $item) {

            $values = array(
                'dbid' => $item['name'],
                'keyword' => mysqli_real_escape_string($this->db->connection, $item['displayname']),
                'searchtext' => mysqli_real_escape_string($this->db->connection, $item['displaydescription']),
                'type' => 'advisor',
                'description' => mysqli_real_escape_string($this->db->connection, $item['displaydescription']),
                'icon' => $item['icon'],

            );
            $this->db->insert('tableofcontents', $values);
        }
    }

    public function get_all_by_age($age, $rarity = null)
    {
        $q = "select advisors.name, advisors.rarity, advisors.icon,
				advisors.age, advisors.minlevel, advisors.itemlevel, advisors.cost,
		        displayname.string as displayname,
		        displaydescription.string as displaydescription
		        FROM  advisors
		        LEFT JOIN strings AS displayname on displayname.stringid = advisors.displaynameid
		        LEFT JOIN strings AS displaydescription on displaydescription.stringid = advisors.displaydescriptionid
		        WHERE advisors.age = {$age} ";

        if ($rarity != null) {
            $q .= "AND rarity='$rarity'";
        }

        $q .= ' ORDER BY displayname';

        $arr = $this->db->query($q)->results();

        return $arr;
    }

    public function get_all()
    {
        $arr = $this->db->query("select advisors.name, advisors.rarity, advisors.icon,
					advisors.age, advisors.minlevel, advisors.itemlevel, advisors.cost,
			        displayname.string as displayname,
			        displaydescription.string as displaydescription
			        FROM  advisors
			        LEFT JOIN strings AS displayname on displayname.stringid = advisors.displaynameid
			        LEFT JOIN strings AS displaydescription on displaydescription.stringid = advisors.displaydescriptionid
			        ORDER BY displayname DESC")->results();

        return $arr;
    }

    public function db_update()
    {
        $this->delete_all();
        $this->db_update_from_api();
    }

    public function db_update_from_xml()
    {

        $XMLReader = new XMLReader();
        $XMLReader->open($this->xmlpath);

        $fields = array(
            'displaynameid',
            'rollovertextid',
            'icon',
            'displaydescriptionid',
            'age',
            'rarity',
            'minlevel',
            'offertype',
            'itemlevel',
        );

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "advisor") {
                continue;
            }

            $doc = new DomDocument('1.0');
            $doc->loadXML($XMLReader->readOuterXml());

            $item['name'] = $doc->documentElement->getAttribute('name');

            // Most of the easy stuff
            foreach ($doc->documentElement->childNodes as $node) {

                if ($node->nodeType != 1) {
                    continue;
                }

                //if it's one of our fields up there
                if (in_array($node->tagName, $fields)) {
                    $item[$node->tagName] = $node->nodeValue;
                } else if ($node->tagName == "sellcostoverride") {
                    $n = $node->getElementsbyTagName('capitalresource')->item(0);
                    $item['cost'] = $n->getAttribute('quantity');
                } else if ($node->tagName == "techs") {
                    $item['tech'] = $node->childNodes->item(1)->nodeValue;
                }
            }

            // Fix icon filenames
            $item = str_replace('\\', '/', $item);

            $this->quicksave($item);

            unset($tech);
        }
    }
    public function db_update_from_api()
    {
        $response = json_decode(file_get_contents("https://api.projectceleste.com/game/advisors/"), true);

        $fields = array(
            'displaynameid',
            'rollovertextid',
            'icon',
            'displaydescriptionid',
            'age',
            'rarity',
            'minlevel',
            'offertype',
            'itemlevel');

        foreach ($response['data'] as $name => $advisorEntry) {
            $advisor['name'] = $name;
            foreach ($fields as $fieldName) {
                if (isset($advisorEntry[$fieldName])) {
                    $advisor[$fieldName] = $advisorEntry[$fieldName];
                } else {
                    $advisor[$fieldName] = null;
                }
            }
            // fix icon paths
            if (isset($advisor['icon'])) {
                $advisor['icon'] = str_replace('\\', '/', $advisor['icon']);
            }

            if (isset($advisorEntry['techs'])) {
                $advisor['tech'] = $advisorEntry['techs']['tech'];
            }
            
            if (isset($advisorEntry['sellcostoverride']) && isset($advisorEntry['sellcostoverride']['capitalresource'])) {
                $advisor['cost'] = $advisorEntry['sellcostoverride']['capitalresource']['quantity'];
            }

            $this->quicksave($advisor);
        }

        echo 'finished processing advisors.';
    }
}
