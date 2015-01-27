<?php
namespace RAAS\CMS\Meta_Checker;

class ViewSub_Main extends \RAAS\Abstract_Sub_View
{
    protected static $instance;
    
    public function metas(array $IN = array())
    {
        $this->assignVars($IN);
        $this->title = $IN['Form']->caption;
        $this->contextmenu = $this->getMetasContextMenu();
        $this->template = 'metas.tmp.php';
    }


    public function getMetasContextMenu() 
    {
        $arr = array();
        $arr[] = array(
            'href' => $this->url . '&action=delete', 
            'name' => $this->_('CLEAR_CACHE'), 
            'icon' => 'remove',
        );
        return $arr;
    }
}