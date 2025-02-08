app.component('oc-tabs', {
    template: $TEMPLATES['oc-tabs'],

    mounted() {
        window.addEventListener('stepActive', this.setStepActive);
    },
    props: {
        entity: {
            type: Entity,
            required: true
        },
        groups: {
            type: Object,
            default: {},
            required: true
        },
        initialGroup: {
            type: String,
            default: ""
        }
    },
    watch: {
        'step'(_new, _old) {
            if (_new != _old) {
                this.changeStep(_new)
            }
        }
    },
    data() {
        return {
            activeTab: this.groups[this.initialGroup],
            actioveOption: null
        }
    },

    methods: {
        changeStep(group) {
            this.activeTab = [];
            this.activeTab = this.groups[group];
        },

        changeOption(ref) {
            this.activeTab.forEach(item => {
                item.isActive = false;
                this.actioveOption = null;

                if (item.ref === ref) {
                    window.dispatchEvent(new CustomEvent('useActions', { detail: { useActions: item.useActions } }));
                    item.isActive = true;
                    this.actioveOption = ref
                }
            });
        },

        setStepActive(data) {
            let step = data.detail.stepActive;
            this.activeTab = this.groups[step]
        }
    }
});
