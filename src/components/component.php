<?php
/**
 * Component class.
 * All components to the same system are of this type
 * Todos os componentes de um sistema tem esse tipo
 * @author lspereira
 *
 */
class component
{
    //system parent
    public $parent, $system;
    public $load;
    public $config;
    public $de;

    /**
     * Constructs a new component.
     * Sets a parent application (system global if no argument passed)
     *
     * Constroi um novo componente.
     * Se um applicativo "pai" nao for passado, usa o global (system)
     * @param $parent
     * @return unknown_type
     */
    public function __construct(&$parent = null)
    {
        global $system;

        //If we don't have a parent, we take the global system as our parent
        if (!$parent) {
            $parent = &$system;
        }

        $this->parent = &$parent;
    }
}
