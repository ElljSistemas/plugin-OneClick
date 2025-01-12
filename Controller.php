<?php

namespace OneClick;

use \MapasCulturais\App;

use MapasCulturais\Traits\ControllerAPI;

class Controller  extends \MapasCulturais\Controllers\EntityController
{
    use ControllerAPI;

    function __construct()
    {
        parent::__construct();
        $this->entityClassName = '\OneClick\Settings';
    }

    public function GET_steps()
    {
        $app = App::i();
        
        $this->requireAuthentication();

        if(!$app->user->is('admin')) {
            $app->pass();
        }

        $this->render('settings', []);
    }

}
