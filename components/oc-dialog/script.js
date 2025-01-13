app.component('oc-dialog', {
    template: $TEMPLATES['oc-dialog'],

    props: {},
    data() {
        return {
            toggle: true
        }
    },
    methods: {
        toggleDialog() {
            this.toggle = !this.toggle
        }
    }
});
