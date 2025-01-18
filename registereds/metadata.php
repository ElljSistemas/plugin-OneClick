<?php

use MapasCulturais\i;

$metadata = [
    // Configurações iniciais - EMAIL
    'mailer_email' => [
        'label' => i::__('Email'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("O email é obrigatório")
        ]
    ],
    'mailer_user' => [
        'label' => i::__('Usuário'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("O usuário é obrigatório")
        ]
    ],
    'mailer_host' => [
        'label' => i::__('Servidor Host'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("O servidor host é obrigatório")
        ]
    ],
    'mailer_protocol' => [
        'label' => i::__('Protocolo'),
        'type' => 'select',
        'private' => true,
        'options' => [
            'LOCAL' => 'Local',
            'SSL' => 'SSL',
            'TLS' => 'TLS',
        ],
        'validations' => [
            'required' => \MapasCulturais\i::__("O protocolo é obrigatório")
        ]
    ],
    'mailer_password' => [
        'label' => i::__('Senha'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A senha é obrigatória")
        ]
    ],
    'mailer_repassword' => [
        'label' => i::__('Confirme a senha'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A confirmação da senha é obrigatória")
        ]
    ],
    // Configurações iniciais - reCaptcha
    'recaptcha_secret' => [
        'label' => i::__('Chave secreta'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A chave secreta é obrigatório")
        ]
    ],
    'recaptcha_sitekey' => [
        'label' => i::__('Chave do site'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A chave do site é obrigatório")
        ]
    ],
    // Configurações iniciais - Georreferenciamento
    'geodivisions' => [
        'label' => i::__('Divisões geográficas'),
        'type' => 'json',
    ],
    'geoDivisionsFilters' => [
        'label' => i::__('Filtro de unidades federativas'),
        'type' => 'json',
    ],
    'zoom_default' => [
        'label' => i::__('Zoom padrão do mapa'),
        'type' => 'text',
    ],
    'zoom_max' => [
        'label' => i::__('Zoom máximo do mapa'),
        'type' => 'text',
    ],
    'zoom_min' => [
        'label' => i::__('Zoom mínimo do mapa'),
        'type' => 'text',
    ],
    'latitude' => [
        'label' => i::__('Latitude'),
        'type' => 'text',
    ],
    'longitude' => [
        'label' => i::__('Longitude'),
        'type' => 'text',
    ],
];

return $metadata;
