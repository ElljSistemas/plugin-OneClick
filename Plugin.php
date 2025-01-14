<?php

namespace OneClick;

use Exception;
use OneClick\Settings;
use MapasCulturais\App;
use MapasCulturais\i;

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
     * @return Settings 
     */
    public function getSettings(): Settings
    {
        $app = App::i();

        $subsiteId = $app->subsite ? $app->subsite->id : null;

        $settings = $app->repo('OneClick\\Settings')->findOneBy(['subsiteId' => $subsiteId]);

        return $settings;
    }
}
