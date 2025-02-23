app.component('configuration-steps', {
    template: $TEMPLATES['configuration-steps'],

    setup() {
        const text = Utils.getTexts('configuration-steps')
        const messages = useMessages();
        const globalState = useGlobalState();
        return { text, messages, globalState }
    },

    computed: {
        settingsId() {
            return $MAPAS.config.oneClick.settingsId
        }
    },
    data() {
        this.globalState.useActions = 'nouse-global';

        let tabGroups = {
            'settings': [
                { label: 'Email', isActive: true, submenu: [], ref: "email", useActions: true },
                { label: 'reCaptcha', isActive: false, submenu: [], ref: "recaptcha", useActions: true },
                { label: 'Georreferenciamento', isActive: false, submenu: [], ref: "georeferencing", useActions: true },
                { label: 'Redes sociais', isActive: false, submenu: [], ref: "socialmedia", useActions: true },
            ],
            'text-image': [
                { label: 'Banner', isActive: false, submenu: [], ref: "banner", useActions: true },
                { label: 'Entidades', isActive: false, submenu: [], ref: "entities", useActions: true },
                { label: 'Em destaque', isActive: false, submenu: [], ref: "feature", useActions: true },
                { label: 'Cadastre-se', isActive: false, submenu: [], ref: "register", useActions: true },
                { label: 'Mapa', isActive: false, submenu: [], ref: "map", useActions: true },
                { label: 'Desenvolvedores', isActive: false, submenu: [], ref: "developer", useActions: true },
                { label: 'Diversas', isActive: false, submenu: [], ref: "complementary", useActions: true },
            ],
        }

        return {
            tabGroups,
            isLoading: false,
            emailTest: null
        }
    },
    methods: {
        sendEmailTest() {
            this.isLoading = true;
            const api = new API();
            let url = Utils.createUrl('settings', 'sendMailTest');
            api.POST(url, { email: this.emailTest }).then(res => res.json()).then(response => {
                this.isLoading = false;
                if (response) {
                    this.emailTest = null;
                    this.messages.success(this.text('sendEmailTestSuccess'));
                } else {
                    this.messages.error(this.text('sendEmailTestError'));
                }
            });
        }
    }
});
