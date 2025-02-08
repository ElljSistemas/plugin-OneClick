app.component('oc-text-image', {
    template: $TEMPLATES['oc-text-image'],

    props: {
        entity: {
            type: Entity,
            required: true
        },
        slug: {
            type: String,
            required: ''
        }
    },
    computed: {
        tabGroups() {
            {
                return {
                    tabs: [
                        { label: 'Texto', isActive: true, submenu: [], ref: 'text', useActions: true },
                        { label: 'Imagem', isActive: false, submenu: [], ref: 'image', useActions: false },
                    ],
                }
            }
        }
    },
    data() {
        return {}
    },
    methods: {

    }
});
