app.component('oc-actions', {
    template: $TEMPLATES['oc-actions'],
    mounted() {
        window.addEventListener('useActions', this.changeUseActions);
    },
    props: {
        entity: {
            type: Entity,
            required: true
        }
    },
    data() {
        return {
            useActions: true
        }
    },
    methods: {
        changeUseActions(data) {
            this.useActions = data.detail.useActions;
        }
    }
});
