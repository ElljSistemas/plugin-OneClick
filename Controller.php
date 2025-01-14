<?php

namespace OneClick;

use InvalidArgumentException;
use Exception;
use \MapasCulturais\App;
use MapasCulturais\Exceptions\MailTemplateNotFound;
use MapasCulturais\Exceptions\NotFound;
use MapasCulturais\Traits\ControllerAPI;
use RuntimeException;
use TypeError;
use Throwable;

class Controller  extends \MapasCulturais\Controllers\EntityController
{
    use ControllerAPI;

    function __construct()
    {
        parent::__construct();
        $this->entityClassName = '\OneClick\Settings';
    }

    /**
     * @return void 
     * @throws RuntimeException 
     * @throws InvalidArgumentException 
     * @throws NotFound 
     * @throws Exception 
     */
    public function GET_steps(): void
    {
        $app = App::i();
        
        $this->requireAuthentication();

        if(!$app->user->is('admin')) {
            $app->pass();
        }

        $this->render('settings', []);
    }


    /**
     * @return void 
     * @throws RuntimeException 
     * @throws InvalidArgumentException 
     * @throws NotFound 
     * @throws Exception 
     * @throws MailTemplateNotFound 
     * @throws TypeError 
     * @throws Throwable 
     */
    public function POST_sendMailTest(): void
    {
        $app = App::i();

        $this->requireAuthentication();

        if(!$app->user->is('admin')) {
            $app->pass();
        }

        $email = $this->data['email'];
        $params = [
            'siteName' => $app->siteName
        ];

        $message = $app->renderMailerTemplate('email_teste_settings', $params);
        $email_params = [
            'from' => $app->config['mailer.from'],
            'to' =>$email,
            'subject' => $message['title'],
            'body' => $message['body'],
        ];

        $send = $app->createAndSendMailMessage($email_params);
        $this->json($send);
    }
    

}
