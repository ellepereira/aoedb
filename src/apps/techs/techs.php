<?php

class techs extends app
{
    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->load->app('aoeo');

    }

    public function c_index()
    {
        $this->aoeo->header();
        $this->aoeo->footer();

    }

}
