app.component('oc-steps', {
    template: $TEMPLATES['oc-steps'],
    props: {
        entity: {
            type: Entity,
            required: true
        }
    },
    data() {
        return {
            stepActive: 'settings',
            steps: [
                { label: 'Configurações iniciais', icons: ["one-click-settings"], isActive: true, ref: 'settings', useActions: true },
                { label: 'Textos e imagens', icons: ["one-click-text-outline", 'one-click-image'], isActive: false, ref: 'text-image', useActions: true },
                { label: 'Cores', icons: ["one-click-colors-sharp"], isActive: false, ref: 'colors', useActions: true },
            ]
        }
    },
    created() {
        this.dispatchStepActive();
    },
    computed: {
      
    },
    methods: {
        hasActive() {
            for (step of this.steps) {
                if (step.isActive) {
                    return true;
                }
                return false;
            }
        },
        changeStep(ref) {
            if (ref != this.stepActive) {
                for (step of this.steps) {
                    step.isActive = false;
                    if (step.ref == ref) {
                        step.isActive = true;
                        this.stepActive = ref;
                    }
                }

                this.dispatchStepActive();
            }
        },

        dispatchStepActive() {
            window.dispatchEvent(new CustomEvent('stepActive', { detail: { stepActive: this.stepActive } }));
        },
    }
});
