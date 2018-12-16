<?php

class item extends model
{
    protected $xmlpath;
    public $info, $level;

    public function __construct(&$parent, $level = null)
    {
        parent::__construct($parent);

        $this->table = 'traits';
        $this->idfield = 'tr_id';
        $this->orderby_field = 'tr_id';
        $this->level = $level;
    }

    public function db_update_from_api()
    {
        $response = json_decode(file_get_contents("https://api.projectceleste.com/game/traits/"), true);

        $traitAttributes = array('dbid',
            'name',
            'displaynameid',
            'rarity',
            'traittype',
            'icon',
            'offertype',
            'tradeable',
            'destroyable',
            'sellable',
            'rollovertextid');

        foreach ($response['data'] as $name => $traitEntry) {
            $traitEntry['name'] = $name;
            foreach ($traitAttributes as $traitAttribute) {
                if (isset($traitEntry[$traitAttribute])) {
                    $trait[$traitAttribute] = $traitEntry[$traitAttribute];
                } else {
                    $trait[$traitAttribute] = null;
                }
            }

            if (isset($traitEntry['effects'])) {
                $effects = $traitEntry['effects']['effect'];

                if (is_array($effects)) {
                    foreach ($effects as $effect) {
                        $effect['dbid'] = $trait['dbid'];
                        $this->add_effect($effect);
                    }
                } else {
                    $effects['dbid'] = $effects['dbid'];
                    $this->add_effect($effects);
                }
            }
            // fix icon paths
            if (isset($trait['icon'])) {
                $trait['icon'] = str_replace('\\', '/', $trait['icon']);
            }

            $trait['levels'] = implode('|', $traitEntry['itemlevels']);
            $this->quicksave($trait);
            echo '<pre>';
            print_r($trait);
            echo '</pre>';
            unset($trait);
        }
    }

    private function add_effect($effect)
    {
        $techEffectAttributes = array('type',
            'dbid',
            'amount',
            'scaling',
            'subtype',
            'allactions',
            'relativity',
            'action',
            'bonus',
            'unittype',
            'resource',
            'damagetype');

        foreach ($techEffectAttributes as $techEffectAttribute) {
            if (isset($effect[$techEffectAttribute])) {
                $traiteffect[$techEffectAttribute] = $effect[$techEffectAttribute];
            } else {
                $traiteffect[$techEffectAttribute] = null;
            }
        }
        print_r($traiteffect);
        $this->db->insert('traiteffects', $traiteffect);
    }

    /**
     * Method to update all database entries by re-reading the XML data files for traits
     * WARNING will overright any changes made on the database
     */
    public function db_update()
    {
        $this->delete_all();
        $this->db->clear_table('traiteffects');
        $this->db_update_from_api();
    }

    public function db_update_from_xml()
    {
        $this->xmlpath = $this->config['aoeo']['traitspath'];
        $XMLReader = new XMLReader();
        $XMLReader->open($this->xmlpath);

        $traitlevels = $this->fetch_levels();

        $techfields = array('dbid',
            'displaynameid',
            'rarity',
            'traittype',
            //'visualfactor',
            'icon',
            'offertype',
            'tradeable',
            'destroyable',
            'sellable',
            'rollovertextid');

        $techEffectAttributes = array('type',
            'amount',
            'scaling',
            'subtype',
            'allactions',
            'relativity',
            'action',
            'bonus',
            'unittype',
            'resource',
            'damagetype');

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "trait") {
                continue;
            }

            $doc = new DomDocument('1.0');
            $doc->loadXML($XMLReader->readOuterXml());

            $trait['name'] = $doc->documentElement->getAttribute('name');
            //$trait['type'] = $doc->documentElement->getAttribute('type');

            // Most of the easy stuff
            foreach ($doc->documentElement->childNodes as $node) {
                if ($node->nodeType != 1) {
                    continue;
                }

                if (in_array($node->tagName, $techfields)) {
                    $trait[$node->tagName] = $node->nodeValue;
                }

            }

            // Effects
            $techEffects = $doc->documentElement->getElementsByTagName('effects');
            if ($techEffects->length > 0) {
                $techEffects = $techEffects->item(0);
                $techEffects = $techEffects->getElementsByTagName('effect');

                if ($techEffects->length > 0) {

                    foreach ($techEffects as $techEffectElement) {
                        $techEffect['dbid'] = $trait['dbid'];

                        foreach ($techEffectAttributes as $techEffectAttribute) {
                            $techEffect[$techEffectAttribute] = $techEffectElement->getAttribute($techEffectAttribute);
                        }

                        $this->db->insert('traiteffects', $techEffect);

                        //print_r($techEffect);

                        unset($techEffect);
                    }
                }
            }

            // Fix icon filenames
            $trait = str_replace('\\', '/', $trait);

            //if we have the levels
            if (isset($traitlevels[$trait['name']])) {
                $trait['levels'] = $traitlevels[$trait['name']];
            } else if (stripos($trait['traittype'], 'Vanity') === false) {
                continue;
            }

            $this->quicksave($trait);
            echo '<pre>';
            print_r($trait);
            echo '</pre>';
            unset($trait);
        }
    }

    private function fetch_levels()
    {
        $this->xmlpath = $this->config['aoeo']['traitslevelspath'];
        $XMLReader = new XMLReader();
        $XMLReader->open($this->xmlpath);

        $traits = array();

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "trait") {
                continue;
            }

            $doc = new DomDocument('1.0');
            $doc->loadXML($XMLReader->readOuterXml());

            if ($doc->documentElement->getAttribute('isdeprecated') == "true") {
                continue;
            }

            $levels = array();

            foreach ($doc->documentElement->childNodes as $node) {
                if ($node->nodeType != 1) {
                    continue;
                }

                if ($node->nodeValue) {
                    $levels = array_merge($levels, explode(",", $node->nodeValue));
                }
            }

            $levels = array_unique($levels, SORT_NUMERIC);

            $traits[$doc->documentElement->getAttribute('name')] = implode("|", $levels);
        }
        return $traits;
    }

    public function db_update_toc()
    {
        $this->db->query("DELETE FROM tableofcontents WHERE type='trait'");

        $all = $this->get_all();

        foreach ($all as $item) {
            $effects = '';

            foreach ($item['effectstrings'] as $effect) {
                $effects .= ' ' . $effect;
            }
            $values = array(
                'dbid' => $item['dbid'],
                'keyword' => mysql_real_escape_string($item['DisplayName']),
                'searchtext' => mysql_real_escape_string($item['RolloverText']),
                'type' => 'trait',
                'description' => $effects,
                'icon' => $item['icon'],

            );
            $this->db->insert('tableofcontents', $values);
        }
    }

    /*function types()
    {
    $path ='c:\\aoeofiles\\data\\\\data\\traittypes.xml';

    $XMLReader = new XMLReader();
    $XMLReader->open($path);

    echo "\$config = array(";
    while ($XMLReader->read()) {
    if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "traittype")
    continue;

    $doc = new DomDocument('1.0');
    $doc->loadXML($XMLReader->readOuterXml());

    $name = $doc->documentElement->getAttribute('name');
    $displaynameid = $doc->documentElement->getElementsByTagName('displaynameid')->item(0)->nodeValue;

    $r = $this->db->query("SELECT * FROM strings WHERE stringid={$displaynameid}")->results();

    $displayname = explode(' - ', $r['string']);
    $displayname = explode(' Equipment ', $displayname[0]);

    $displayname = $displayname[0];

    echo "'$name' => '$displayname' , \n <br />";

    }

    echo "'');";

    }*/

    public function load($id, $level = null)
    {
        if ($level != null) {
            $this->level = $level;
        }

        $this->info = $this->get($id);

        return $this;
    }

    public function get($id)
    {
        $arr = $this->db->query("SELECT traits.*,
					    displayname.string as DisplayName, rollovertext.string as RolloverText
					    FROM traits
					    LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
					    LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID
						WHERE dbid={$id} LIMIT 1")->results();

        if ($this->level != null) {
            $arr['level'] = $this->level;
        } else if (!empty($arr['levels'])) {
            $levels = explode('|', $arr['levels']);
            arsort($levels);
            $arr['level'] = $levels[0] + 3;
        } else {
            $arr['level'] = 43;
        }

        $arr['effectstrings'] = $this->get_effects($arr['dbid'], $arr['level']);

        $arr['type'] = $this->config[$arr['traittype']];

        return $arr;
    }

    public function get_effects($dbid, $level = null)
    {

        $effect_strings = array();
        $itemeffects = $this->db->query("SELECT * FROM traiteffects WHERE DBID={$dbid}")->results(null, true);

        foreach ($itemeffects as $effect) {
            $m = new traiteffect($this->parent, $level);
            $m->loadByArray($effect);
            $effect_strings[] = $m;
        }

        return $effect_strings;
    }

    public function get_all()
    {
        $arr = $this->db->query("select traits.*,
				    displayname.string as DisplayName, rollovertext.string as RolloverText
				    FROM traits
				    LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
					LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID
					ORDER BY displayname ASC")->results();

        foreach ($arr as $k => $item) {
            $arr[$k]['effectstrings'] = $this->get_effects($item['dbid']);
            $arr[$k]['type'] = $this->config[$item['traittype']];
        }

        return $arr;
    }

    public function GetPaginated($type, $page = 1, $page_size = 25)
    {
        $startAt = ($page - 1) * $page_size;
        $q = 'select traits.*,
			displayname.string as DisplayName, rollovertext.string as RolloverText
			FROM traits
			LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
			LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID';

        if ($type !== null) {
            $q .= " WHERE traittype='{$type}'";
        }

        $q .= " ORDER BY displayname ASC LIMIT {$startAt},{$page_size}";

        $arr = $this->db->query($q)->results();

        foreach ($arr as $k => $item) {
            $arr[$k]['effectstrings'] = $this->get_effects($item['dbid']);
            $arr[$k]['type'] = $this->config[$item['traittype']];
        }

        return $arr;
    }

    public function GetCount($type)
    {
        $q = 'SELECT COUNT(*) FROM traits';
        if ($type !== null) {
            $q .= " WHERE traittype='{$type}'";
        }
        return $this->db->query($q)->results()["COUNT(*)"];
    }

    public function search($term)
    {
        return $this->db->query("SELECT * FROM tableofcontents WHERE keyword LIKE '%{$term}%' AND type='trait' LIMIT 100")->results(null, true);
    }

    public function get_all_by_type($type)
    {
        $arr = $this->db->query("select *,
				    displayname.string as DisplayName, rollovertext.string as RolloverText
				    FROM traits
				    LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
				    LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID
					WHERE traittype='{$type}' ORDER BY displayname")->results();

        foreach ($arr as $k => $r) {
            //$arr[$k]['effectstrings'] = $this->get_effects($r['dbid']);
            $arr[$k]['type'] = $this->config[$r['traittype']];
        }

        return $arr;
    }
}
