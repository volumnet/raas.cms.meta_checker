<?php
namespace RAAS\CMS\Meta_Checker;

class Webmaster extends \RAAS\CMS\Webmaster
{
    protected static $instance;

    public function __get($var)
    {
        switch ($var) {
            default:
                return \RAAS\CMS\Meta_Checker\Module::i()->__get($var);
                break;
        }
    }
}