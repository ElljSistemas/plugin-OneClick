app.component('configuration-steps', {
    template: $TEMPLATES['configuration-steps'],

    computed: {
        settingsId() {
            return $MAPAS.config.oneClick.settingsId
        }
    },
    data() {
        let tabGroups = {
            'settings': [
                { label: 'Email', isActive: true, submenu: [], ref: "email" },
                { label: 'reCaptcha', isActive: false, submenu: [], ref: "recaptcha" },
                { label: 'Georreferenciamento', isActive: false, submenu: [], ref: "georreferenciamento" },
                { label: 'Redes sociais', isActive: false, submenu: [], ref: "redessociais" },
            ],
            'text-image': [
                { label: 'Banner', isActive: true, submenu: [], ref: "banner" },
                {
                    label: 'Entidades', isActive: false, submenu: [
                        { label: 'Oportunidades', isActive: false, ref: "oportunidades" },
                        { label: 'Eventos', isActive: false, ref: "eventos" },
                        { label: 'Espaços', isActive: false, ref: "espaços" },
                        { label: 'Agentes', isActive: false, ref: "agentes" },
                        { label: 'Projetos', isActive: false, ref: "projetos" },
                    ]
                },
                { label: 'Em destaque', isActive: false, submenu: [], ref: "emdestaque" },
                { label: 'Cadastre-se', isActive: false, submenu: [], ref: "cadastrese" },
                { label: 'Mapa', isActive: false, submenu: [], ref: "mapa" },
                { label: 'Desenvolvedor', isActive: false, submenu: [], ref: "desenvolvedores" },
            ],
        }

        return {
            tabGroups,
        }
    },
    methods: {}
});
