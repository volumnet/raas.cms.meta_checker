<?php
namespace RAAS\CMS\Meta_Checker;
use \RAAS\Redirector;

class Sub_Main extends \RAAS\Abstract_Sub_Controller
{
    protected static $instance;
    
    public function run()
    {
        switch ($this->action) {
            case 'delete':
                $this->model->deleteCache();
                new Redirector($this->url);
                break;
            default:
                $this->metas();
                break;
        }
    }

    
    protected function metas()
    {
        $OUT = $this->model->getMetas($_GET);
        $OUT['pagesVar'] = 'page';
        $OUT['url'] = $this->url;
        $this->view->metas($OUT);
    }
}