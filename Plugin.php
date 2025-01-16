<?php

namespace OneClick;

use Exception;
use MapasCulturais\i;
use OneClick\Settings;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin
{
    public function __construct($config = [])
    {
        $config += [];

        parent::__construct($config);
    }

    /**
     * @return void 
     */
    public function _init(): void
    {
        $app = App::i();

        $self = $this;

        $app->view->enqueueStyle('app-v2', 'OneClick-v2', 'css/plugin-OneClick.css');

        $driver = $app->em->getConfiguration()->getMetadataDriverImpl();
        $driver->addPaths([__DIR__]);

        // Insere a entidade no EntitiesDescription
        $app->hook('mapas.printJsObject:before', function () {
            $this->jsObject['EntitiesDescription']['settings'] = Settings::getPropertiesMetadata();
        });

        // Define a entidade na lista de ENUM
        $app->hook('doctrine.emum(object_type).values', function (&$values) {
            $values['Settings'] = Settings::class;
        });

        // Personalização de ícones
        $app->hook('component(mc-icon).iconset', function (&$iconset) {
            $iconset['one-click-brush'] = "la:brush";
            $iconset['one-click-settings'] = "ic:outline-settings";
            $iconset['one-click-text-outline'] = "mdi:card-text-outline";
            $iconset['one-click-image'] = "majesticons:image";
            $iconset['one-click-colors-sharp'] = "material-symbols:colors-sharp";
            $iconset['one-click-dialog'] = 'wpf:ask-question';
            $iconset['one-click-close-rounded'] = 'material-symbols:close-rounded';
        });

        // Garante o registro de metadados em todas as requisições
        $app->hook('<<*>>(<<*>>.<<*>>):before', function () use ($self) {
            $self->oneClickRegisteredMetadata();
        });

        // hook responsável por setar as configurações em seus devidos lugares
        $app->hook('app.register:after', function () use ($self, $app) {
            $app->disableAccessControl();

            $settings = $self->getSettings();

            if($settings) {
                $self->setEmailSettings($settings, $app);
                $self->setRecaptchaSettings($settings, $app);
            }

            $app->enableAccessControl();
        });
    }

    /**
     * @return void 
     * @throws Exception 
     */
    public function register(): void
    {
        $app = App::i();

        $app->registerController('settings', Controller::class);

        $this->oneClickRegisteredMetadata();
    }

    /**
     * @return void 
     */
    public function oneClickRegisteredMetadata(): void
    {
        $app = App::i();
        include __DIR__ . "/registereds/metadata.php";
        foreach ($metadata as $key => $cfg) {
            $this->registerMetadata('OneClick\\Settings', $key, $cfg);
        }
    }

    /**
     * @param Settings $settings 
     * @return void 
     */
    public function setEmailSettings(\OneClick\Settings $settings, App $app): void
    {
        $app->config['mailer.templates']['email_teste_settings'] = [
            'title' => i::__("{$app->siteName} - Teste de configuração de email"),
            'template' => 'email_teste_settings.html'
        ];

        $mailer_trasport = "smtp://";

        if ($settings->mailer_user) {
            $mailer_trasport .= $settings->mailer_user;
        }

        if ($settings->mailer_password) {
            $mailer_trasport .= ":{$settings->mailer_password}";
        }

        if ($settings->mailer_host) {
            $mailer_trasport .= "@{$settings->mailer_host}";
        }

        if ($settings->mailer_protocol && $settings->mailer_protocol !== "LOCAL") {
            $mailer_trasport .= $settings->mailer_protocol === 'SSL' ? ':465' : ':587';
        } else {
            $mailer_trasport .= ":1025";
        }

        $app->config['mailer.transport'] = $mailer_trasport;
        $app->config['mailer.from'] = $settings->mailer_email ? $settings->mailer_email : "sysadmin@localhost";
    }

    /**
     * @param Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setRecaptchaSettings(\OneClick\Settings $settings, App $app): void
    {
        $auth = [];
        if ($settings->recaptcha_secret) {
            $auth['google-recaptcha-secret'] = $settings->recaptcha_secret;
        }

        if ($settings->recaptcha_sitekey) {
            $auth['google-recaptcha-sitekey'] = $settings->recaptcha_sitekey;
        }

        if ($settings->recaptcha_sitekey && $settings->recaptcha_secret) {
            file_put_contents(__DIR__ . "/files/auth.txt", json_encode($auth));
        }
    }


    /**
     * @return Settings 
     */
    public function getSettings(): ?Settings
    {
        $app = App::i();

        $subsiteId = $app->subsite ? $app->subsite->id : null;

        if(!$settings = $app->repo('OneClick\\Settings')->findOneBy(['subsiteId' => $subsiteId])) {
            $settings = $app->repo('OneClick\\Settings')->findOneBy(['id' => 1]);
        }

        return $settings;
    }
}
