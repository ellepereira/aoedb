<?php

class units extends app
{
    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->load->app('aoeo');

        $this->config['protopath'] = $this->aoeo->config['protopath'];

        $this->load->model('proto');

    }

    protected function header($title = 'Units')
    {
        $this->aoeo->header($title);

    }

    protected function footer()
    {
        //$this->comments->c_tips();
        $this->aoeo->footer();
    }

    public function c_index($id = null)
    {
        if (!$id) {
            $this->c_civ('gr', 'inf');
        } else if ($this->m_proto->load($id)) {

            $unit = $this->m_proto;
            $unit->info['config'] = $this->config;
            $this->header($unit->info['DisplayName']);
            $this->load->view('unit', $unit->info);

            $this->footer();
        } else {
            $this->header();
            echo 'Could not load unit';
            $this->footer();
        }

    }

    public function c_debug($id)
    {
        $this->header();
        $unit = $this->m_proto->load($id);
        {
            $unit = $this->m_proto;
            $unit->info['config'] = $this->config;
            $this->load->view('unitdebug', $unit->info);
        }
        $this->footer();
    }
    public function xml($name)
    {
        if (is_file($this->config['exportpath'] . 'proto/' . $name . 'xml')) {
            echo file_get_contents($this->config['exportpath'] . 'proto/' . $name . 'xml');
        } else {
            return null;
        }

    }

    public function c_update()
    {
        $this->m_proto->db_update();
        $this->m_proto->db_update_toc();
    }

    public function c_list()
    {
        $this->header();

        $qs = $this->m_proto->get_all();
        foreach ($qs as $q) {
            echo "<a href='/aoeo/units/{$q['DBID']}'>{$q['DisplayName']}</a> <br />";
        }
        $this->footer();
    }

    public function c_type($utype)
    {
        $typetypes = array('' => 'Units', 'inf' => 'Infantry', 'cav' => 'Cavalry', 'bldg' => 'Building(s)', 'arc' => 'Ranged Unit(s)', 'shp' => 'Ship', 'sie' => 'Siege Unit(s)', 'civ' => 'Economic Unit(s)', 'spc' => 'Religious Unit(s)', 'cap' => 'Capital Building(s)');

        $this->header($typetypes[$utype]);

        $units['units'] = $this->m_proto->GetAllByType($utype);
        $units['config'] = $this->config;
        $units['civ'] = '';
        $units['type'] = $utype;
        $this->load->view('unitslist', $units);
        $this->footer();
    }

    public function c_utype($utype)
    {
        $this->header();

        $units['config'] = $this->config;
        $units['units'] = $this->m_proto->GetAllByUType($utype);
        $units['civ'] = 'Every';
        $units['type'] = $utype;
        $this->load->view('unitslist', $units);
        $this->footer();
    }

    public function c_civ($uciv, $type = '')
    {
        $typetypes = array('' => 'Units', 'inf' => 'Infantry', 'cav' => 'Cavalry', 'bldg' => 'Building(s)', 'arc' => 'Ranged Unit(s)', 'shp' => 'Ships', 'sie' => 'Siege Unit(s)', 'civ' => 'Economic Unit(s)', 'spc' => 'Religious Unit(s)', 'cap' => 'Capital Building(s)');
        $civs = array('' => 'All', 'gr' => 'Greek', 'eg' => 'Egyptian', 'ba' => 'Babylonian', 'ce' => 'Celtic', 'pe' => 'Persian', 'con' => 'Consumable', 'pv' => 'Spartan', 'mn' => 'Minoan', 'cy' => 'Cypriot', 'no' => 'Norse');

        $this->header("{$civs[$uciv]} {$typetypes[$type]}");

        $units['config'] = $this->config;
        $units['units'] = $units = $this->m_proto->GetAllByCiv($uciv, $type);
        $units['civ'] = $uciv;
        $units['type'] = $type;
        $this->load->view('unitslist', $units);
        $this->footer();
    }

    public function c_units()
    {

        $this->header('Units');

        $this->load->view('unitsmain');
        $this->footer();
    }

}

/**end of file*/
