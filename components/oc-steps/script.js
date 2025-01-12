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
                { label: 'Configurações iniciais', icons: ["one-click-settings"], isActive: true, ref: 'settings' },
                { label: 'Textos e imagens', icons: ["one-click-text-outline", 'one-click-image'], isActive: false, ref: 'text-image' },
                { label: 'Cores', icons: ["one-click-colors-sharp"], isActive: false, ref: 'colors' },
            ]
        }
    },
    created() {
        this.dispatchStepActive();
    },
    computed: {
        hasActive() {
            for (step of this.steps) {
                if (step.isActive) {
                    return true;
                }
                return false;
            }
        }
    },
    methods: {
        changeStep(ref) {
            for (step of this.steps) {
                step.isActive = false;
                if (step.ref == ref) {
                    step.isActive = true;
                    this.stepActive = ref;
                }
            }

            this.dispatchStepActive();
        },

        dispatchStepActive() {
            window.dispatchEvent(new CustomEvent('stepActive', { detail: { stepActive: this.stepActive } }));
        },
    }
});
