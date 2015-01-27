<?php
namespace RAAS\CMS\Meta_Checker;

class View_Web extends \RAAS\Module_View_Web
{
    protected static $instance;

    public function header()
    {
        $menuItem = array(array(
            'href' => '?p=' . $this->package->alias . '&m=' . $this->module->alias, 
            'name' => $this->_('__NAME'),
        ));
        $menu = $this->menu->getArrayCopy();
        array_splice($menu, -1, 0, $menuItem);
        $this->menu = new \ArrayObject($menu);
    }
}