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


        if (!$app->repo('DbUpdate')->findBy(['name' => 'create table settings sequence'])) {
            $em = $app->em;
            $conn = $em->getConnection();
        
            // Verificar se a sequência 'oc_settings_id_seq' já existe
            $sequenceExists = $conn->fetchOne("
                SELECT COUNT(*) 
                FROM pg_class c 
                JOIN pg_namespace n ON n.oid = c.relnamespace 
                WHERE c.relkind = 'S' AND c.relname = 'oc_settings_id_seq'
            ");
            if ($sequenceExists == 0) {
                $conn->executeQuery("CREATE SEQUENCE oc_settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
            }
        
            // Verificar se a sequência 'settings_meta_id_seq' já existe
            $sequenceExists = $conn->fetchOne("
                SELECT COUNT(*) 
                FROM pg_class c 
                JOIN pg_namespace n ON n.oid = c.relnamespace 
                WHERE c.relkind = 'S' AND c.relname = 'settings_meta_id_seq'
            ");
            if ($sequenceExists == 0) {
                $conn->executeQuery("CREATE SEQUENCE settings_meta_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
            }
        
            $app->disableAccessControl();
            $db_update = new \MapasCulturais\Entities\DbUpdate;
            $db_update->name = 'create table settings sequence';
            $db_update->save(true);
            $app->enableAccessControl();
            $conn->commit();
        }
        
        if (!$app->repo('DbUpdate')->findBy(['name' => 'create table settings'])) {
            $em = $app->em;
            $conn = $em->getConnection();
        
            // Verificar se a tabela 'settings' já existe
            $tableExists = $conn->fetchOne("
                SELECT COUNT(*) 
                FROM information_schema.tables 
                WHERE table_name = 'settings'
            ");
            if ($tableExists == 0) {
                $conn->executeQuery("
                    CREATE TABLE settings (
                        id INT NOT NULL, 
                        status SMALLINT NOT NULL, 
                        metadata JSON DEFAULT '{}' NOT NULL, 
                        create_timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                        update_timestamp TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                        subsite_id SMALLINT NULL, 
                    PRIMARY KEY(id))
                ");
            }
        
            // Verificar se a tabela 'settings_meta' já existe
            $tableExists = $conn->fetchOne("
                SELECT COUNT(*) 
                FROM information_schema.tables 
                WHERE table_name = 'settings_meta'
            ");
            if ($tableExists == 0) {
                $conn->executeQuery("
                    CREATE TABLE settings_meta (
                        object_id integer NOT NULL,
                        key character varying(32) NOT NULL,
                        value text,
                        id integer NOT NULL
                    );
                ");
            }
        
            $app->disableAccessControl();
            $db_update = new \MapasCulturais\Entities\DbUpdate;
            $db_update->name = 'create table settings';
            $db_update->save(true);
            $app->enableAccessControl();
            $conn->commit();
        }
        
        if (!$app->repo('DbUpdate')->findBy(['name' => 'inserts default settings'])) {
            $em = $app->em;
            $conn = $em->getConnection();
            
            // Settings inicial
            $conn->executeQuery("INSERT INTO settings (id, status, metadata, create_timestamp, update_timestamp, subsite_id) VALUES (nextval('oc_settings_id_seq'::regclass), 1, '{}', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, null)");

            // Email
            $conn->executeQuery("INSERT INTO settings_meta (id, key, value, object_id) VALUES (nextval('settings_meta_id_seq'::regclass), 'mailer_email', 'sysadmin@localhost', 1)");
            $conn->executeQuery("INSERT INTO settings_meta (id, key, value, object_id) VALUES (nextval('settings_meta_id_seq'::regclass), 'mailer_host', 'mailhog', 1)");
            $conn->executeQuery("INSERT INTO settings_meta (id, key, value, object_id) VALUES (nextval('settings_meta_id_seq'::regclass), 'mailer_protocol', 'LOCAL', 1)");
            
            // reCaptcha
            $conn->executeQuery("INSERT INTO settings_meta (id, key, value, object_id) VALUES (nextval('settings_meta_id_seq'::regclass), 'recaptcha_secret', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', 1)");
            $conn->executeQuery("INSERT INTO settings_meta (id, key, value, object_id) VALUES (nextval('settings_meta_id_seq'::regclass), 'recaptcha_sitekey', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', 1)");
        
            $app->disableAccessControl();
            $db_update = new \MapasCulturais\Entities\DbUpdate;
            $db_update->name = 'inserts default settings';
            $db_update->save(true);
            $app->enableAccessControl();
            $conn->commit();
        }
        

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

            $iconset['one-click-facebook'] = 'mdi:facebook';
            $iconset['one-click-instagram'] = 'mdi:instagram';
            $iconset['one-click-linkedin'] = 'mdi:linkedin';
            $iconset['one-click-pinterest'] = 'mdi:pinterest';
            $iconset['one-click-spotify'] = 'mdi:spotify';
            $iconset['one-click-tiktok'] = 'mdi:tiktok';
            $iconset['one-click-x'] = 'mdi:twitter';
            $iconset['one-click-vimeo'] = 'mdi:vimeo';
            $iconset['one-click-youtube'] = 'mdi:youtube';
            $iconset['one-click-upload'] = 'et:upload';

        });

        // Garante o registro de metadados em todas as requisições
        $app->hook('<<*>>(<<*>>.<<*>>):before', function () use ($self) {
            $self->oneClickRegisteredMetadata();
        });

        // hook responsável por setar as configurações em seus devidos lugares
        $app->hook('app.register:after', function () use ($self, $app) {
            $app->disableAccessControl();

            $settings = $self->getSettings();

            if ($settings) {
                $self->setEmailSettings($settings, $app);
                $self->setRecaptchaSettings($settings, $app);
                $self->setGeoSettings($settings, $app);
                $self->setSocialMedia($settings, $app);
                $self->setImagesHome($settings, $app);
                $self->setTextsHome($settings, $app);
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
     * @param null|Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setEmailSettings(?Settings $settings, App $app): void
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
     * @param null|Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setRecaptchaSettings(?Settings $settings, App $app): void
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
     * @param null|Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setGeoSettings(?Settings $settings, App $app): void
    {
        if ($values = $settings->geoDivisionsFilters) {
            $geoDivisionsFilters = [];
            $fromTo = $this->getFromToGeoFilters();
            foreach ($values as $value) {
                $geoDivisionsFilters[] = $fromTo[$value];
            }

            $app->config['app.geoDivisionsFilters'] = $geoDivisionsFilters;
        }

        if ($values = $settings->geodivisions) {
            $geoDivisionsHierarchy = $this->getFromToGeoDivisionsHierarchy();
            foreach ($values as $value) {
                $name = $geoDivisionsHierarchy[$value];
                $app->config['app.geoDivisionsFilters'][$value] = ['name' => $name, 'showLayer' => true];
            }
        }

        if ($settings->zoom_default) {
            $app->config['maps.zoom.default'] = $settings->zoom_default;
        }

        if ($settings->zoom_max) {
            $app->config['maps.zoom.max'] = $settings->zoom_max;
        }

        if ($settings->zoom_min) {
            $app->config['maps.zoom.min'] = $settings->zoom_min;
        }

        if ($settings->latitude && $settings->longitude) {
            $app->config['maps.center'] = [$settings->latitude, $settings->longitude];
        }
    }

    /**
     * @param null|Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setSocialMedia(?Settings $settings, App $app): void
    {
        if ($settings->socialmediaData) {
            $socialMedia = (array) $settings->socialmediaData;
            foreach ($socialMedia as $metadata => $link) {
                $app->config['social-media'][$metadata] = [
                    'icon' => $metadata,
                    'link' => $link
                ];
            }
        }
    }

    /**
     * @param null|Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setImagesHome(?Settings $settings, App $app)
    {
        $public_banner_url = null;
        if($bannerImageData = $settings->bannerImageData) {
            $banner_ile =   basename($bannerImageData->path);
            $app->config['module.home']['home-header'] = "img/home/{$banner_ile}";
            $public_banner_url = $app->view->asset("img/home/{$banner_ile}", false);
        }
        
        $public_opportunity_url = null;
        if($entitiesOpportunityImageData = $settings->entitiesOpportunityImageData) {
            $entities_opportunity_file =   basename($entitiesOpportunityImageData->path);
            $app->config['module.home']['home-opportunities'] = "img/home/{$entities_opportunity_file}";
            $public_opportunity_url = $app->view->asset("img/home/{$entities_opportunity_file}", false);
        }

        $public_event_url = null;
        if($entitiesEventImageData = $settings->entitiesEventImageData) {
            $entities_event_file =   basename($entitiesEventImageData->path);
            $app->config['module.home']['home-events'] = "img/home/{$entities_event_file}";
            $public_event_url = $app->view->asset("img/home/{$entities_event_file}", false);
        }

        $public_space_url = null;
        if($entitiesSpaceImageData = $settings->entitiesSpaceImageData) {
            $entities_space_file =   basename($entitiesSpaceImageData->path);
            $app->config['module.home']['home-spaces'] = "img/home/{$entities_space_file}";
            $public_space_url = $app->view->asset("img/home/{$entities_space_file}", false);
        }

        $public_agent_url = null;
        if($entitiesAgentImageData = $settings->entitiesAgentImageData) {
            $entities_agent_file =   basename($entitiesAgentImageData->path);
            $app->config['module.home']['home-agents'] = "img/home/{$entities_agent_file}";
            $public_agent_url = $app->view->asset("img/home/{$entities_agent_file}", false);
        }

        $public_project_url = null;
        if($entitiesProjectImageData = $settings->entitiesProjectImageData) {
            $entities_project_file =   basename($entitiesProjectImageData->path);
            $app->config['module.home']['home-projects'] = "img/home/{$entities_project_file}";
            $public_project_url = $app->view->asset("img/home/{$entities_project_file}", false);
        }

        $app->view->jsObject['config']['oneClickUploads'] = [
            'home-header' => $public_banner_url,
            'home-opportunities' => $public_opportunity_url,
            'home-events' => $public_event_url,
            'home-spaces' => $public_space_url,
            'home-agents' => $public_agent_url,
            'home-projects' => $public_project_url,
        ];
    }

    /**
     * @param null|Settings $settings 
     * @param App $app 
     * @return void 
     */
    public function setTextsHome(?Settings $settings, App $app)
    {
        if($bannerTitle = $settings->bannerTitle) {
            $app->config['text:home-header.title'] = $bannerTitle;
        }

        if($entitiesTitle = $settings->entitiesTitle) {
            $app->config['text:home-entities.title'] = $entitiesTitle;
        }

        if($entitiesDescription = $settings->entitiesDescription) {
            $app->config['text:home-entities.description'] = $entitiesDescription;
        }

        if($bannerDescription = $settings->bannerDescription) {
            $app->config['text:home-header.description'] = $bannerDescription;
        }

        if($entityOpportunityDescription = $settings->entityOpportunityDescription) {
            $app->config['text:home-entities.opportunities'] = $entityOpportunityDescription;
        }

        if($entityEventDescription = $settings->entityEventDescription) {
            $app->config['text:home-entities.events'] = $entityEventDescription;
        }

        if($entitySpaceDescription = $settings->entitySpaceDescription) {
            $app->config['text:home-entities.spaces'] = $entitySpaceDescription;
        }

        if($entityAgentDescription = $settings->entityAgentDescription) {
            $app->config['text:home-entities.agents'] = $entityAgentDescription;
        }

        if($entityProjectDescription = $settings->entityProjectDescription) {
            $app->config['text:home-entities.projects'] = $entityProjectDescription;
        }
    }

    /**
     * @return Settings 
     */
    public function getSettings(): ?Settings
    {
        $app = App::i();

        $subsiteId = $app->subsite ? $app->subsite->id : null;

        if (!$settings = $app->repo('OneClick\\Settings')->findOneBy(['subsiteId' => $subsiteId])) {
            $settings = $app->repo('OneClick\\Settings')->findOneBy(['id' => 1]);
        }

        return $settings;
    }

    /**
     * @return array 
     */
    public function getFromToGeoFilters(): array
    {
        return [
            'AC' => 12,
            'AL' => 27,
            'AM' => 13,
            'AP' => 16,
            'BA' => 29,
            'CE' => 23,
            'DF' => 53,
            'ES' => 32,
            'GO' => 52,
            'MA' => 21,
            'MG' => 31,
            'MS' => 50,
            'MT' => 51,
            'PA' => 15,
            'PB' => 25,
            'PE' => 26,
            'PI' => 22,
            'PR' => 41,
            'RJ' => 33,
            'RN' => 24,
            'RS' => 43,
            'RO' => 11,
            'RR' => 14,
            'SC' => 42,
            'SE' => 28,
            'SP' => 35,
            'TO' => 17
        ];
    }

    /**
     * @return array 
     */
    public function getFromToGeoDivisionsHierarchy(): array
    {
        return [
            'pais' => i::__('País'),
            'regiao' => i::__('Região'),
            'estado' => i::__('Estado'),
            'mesorregiao' => i::__('Mesorregião'),
            'microrregiao'     => i::__('Microrregião'),
            'municipio' => i::__('Município'),
            'zona' => i::__('Zona'),
            'subprefeitura' => i::__('Subprefeitura'),
            'distrito' => i::__('Distrito'),
            'setor_censitario' => i::__('Setor Censitario')
        ];
    }

    /**
     * @param string $metadata 
     * @return string 
     */
    public function socialmediaLabels(string $metadata): string
    {
        $from_to = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'linkedin' => 'Linkedin',
            'pinterest' => 'Pinterest',
            'spotify' => 'Spotify',
            'tiktok' => 'Tiktok',
            'twitter' => 'X Twitter',
            'vimeo' => 'Vimeo',
            'youtube' => 'Youtube'
        ];

        return $from_to[$metadata];
    }
    
}
