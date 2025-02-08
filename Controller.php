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

    /**
     * @return void 
     * @throws RuntimeException 
     * @throws InvalidArgumentException 
     * @throws NotFound 
     * @throws PermissionDenied 
     * @throws WorkflowRequest 
     */
    public function POST_upload()
    {
        $app = App::i();

        $this->requireAuthentication();

        if (!$app->user->is('admin')) {
            $app->pass();
        }

        if (isset($_FILES['ocFileUpload']) && $_FILES['ocFileUpload']['error'] === UPLOAD_ERR_OK) {
            $oldName = basename($_FILES['ocFileUpload']['name']);
            $fileTmpPath = $_FILES['ocFileUpload']['tmp_name'];
            $new_name = (new DateTime("now"))->getTimestamp();
            $ext = pathinfo($oldName, PATHINFO_EXTENSION);
            $prop = $this->data['prop'];

            $dir = __DIR__ . "/files";
            if (isset($this->data['dir'])) {
                $dir = __DIR__ . "/" . $this->data['dir'];
            }

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $path = $dir . "/" . $new_name.".$ext";
            if (file_exists($path)) {
                unlink($path);
            }

            if ($settings = $app->repo('OneClick\\Settings')->find($this->data['id'])) {

                $bannerImageData = [];
                $old_image = null;
                if ($bannerImageData = $settings->bannerImageData) {
                    $old_image = $bannerImageData->path;
                }

                $bannerImageData = [
                    'prop' => $prop,
                    'path' => $path,
                    'settingsId' => $settings->id,
                    'oldName' => $oldName,
                    'ext' => $ext,
                    'dateUpload' => (new DateTime("now"))->format('Y-m-d H:i:s'),
                    'new_name' => $new_name.".$ext",
                ];

                if (move_uploaded_file($fileTmpPath, $path)) {
                    if ($old_image && file_exists($old_image)) {
                        unlink($old_image);
                    }

                    $settings->bannerImageData = $bannerImageData;
                    $settings->save(true);
                    $this->json($bannerImageData);
                }
            }
        }

        $this->json(false);
    }
}
