<?php
namespace RAAS\CMS\Meta_Checker;

abstract class Abstract_Controller extends \RAAS\Abstract_Module_Controller
{
    protected static $instance;
    
    protected function execute()
    {
        switch ($this->sub) {
            default:
                Sub_Main::i()->run();
                break;
        }
    }
}