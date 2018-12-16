<?php

class designs extends app
{
    public function __construct(&$parent)
    {
        parent::__construct($parent);

        //easy database handler
        $this->load->lib('easydb', $this->db);
        $this->load->app('aoeo');

        $this->config = $this->aoeo->config;
        $this->load->model('design');

    }

    public function c_index($name = null)
    {
        if (!$name) {
            $this->c_type('material');
        } elseif ($this->m_design->load($name)) {
            $this->aoeo->header($this->m_design->displayname . ' Recipe');
            $design = $this->m_design;
            $this->show('design', $design->info);
            $this->aoeo->footer();
        } else {
            $this->aoeo->header('Recipes');
            echo 'Could not load design';
            $this->aoeo->footer();
        }
    }

    public function c_type($type)
    {

        $this->aoeo->header(ucfirst($type) . ' Recipes');
        $designs = $this->m_design->get_all_by_type($type);
        $this->show('designlist', $designs);
        $this->aoeo->footer();
    }

    public function c_update()
    {
        $this->m_design->db_update();
    }
}
