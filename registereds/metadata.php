<?php

use MapasCulturais\i;

$metadata = [
    // Configurações iniciais - EMAIL
    'email' => [
        'label' => i::__('Email'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("O email é obrigatório")
        ]
    ],
    'port' => [
        'label' => i::__('Porta'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A porta é obrigatória")
        ],
    ],
    'protocol' => [
        'label' => i::__('Protocolo'),
        'type' => 'select',
        'private' => true,
        'options' => [
            '' => 'Selecione o protocolo',
            'SSL' => 'SSL',
            'TLS' => 'TLS',
        ],
        'validations' => [
            'required' => \MapasCulturais\i::__("O protocolo é obrigatório")
        ]
    ],
    'password' => [
        'label' => i::__('Senha'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A senha é obrigatória")
        ]
    ],
    'repassword' => [
        'label' => i::__('Confirme a senha'),
        'type' => 'string',
        'private' => true,
        'validations' => [
            'required' => \MapasCulturais\i::__("A confirmação da senha é obrigatória")
        ]
    ],
];

return $metadata;
