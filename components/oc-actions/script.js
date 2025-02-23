app.component('oc-actions', {
    template: $TEMPLATES['oc-actions'],
    setup() {
        const text = Utils.getTexts('evaluation-actions')
        const globalState = useGlobalState();
        return { text, globalState }
    },

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
        let useActions = this.globalState.useActions === 'nouse-global' ? true : this.globalState.useActions;
        
        return {
            useActions: useActions
        }
    },
    methods: {
        changeUseActions(data) {
            this.useActions = data.detail.useActions;
        }
    }
});
