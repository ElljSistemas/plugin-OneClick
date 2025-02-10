app.component('oc-entities', {
    template: $TEMPLATES['oc-entities'],

    props: {
        entity: {
            type: Entity,
            required: true
        }
    },
    computed: {
        tabGroups() {
            {
                return {
                    tabs: [
                        { label: 'Oportunidades', isActive: true, submenu: [], ref: 'opportunity', useActions: true },
                        { label: 'Eventos', isActive: false, submenu: [], ref: 'event', useActions: true },
                        { label: 'Espaços', isActive: false, submenu: [], ref: 'space', useActions: true },
                        { label: 'Agentes', isActive: false, submenu: [], ref: 'agent', useActions: true },
                        { label: 'Projetos', isActive: false, submenu: [], ref: 'project', useActions: true },
                    ],
                }
            }
        }
    },
    data() {
        return {}
    },
    methods: {}
});
