<template lang="html">
    <div class="">
        
    </div>
</template>

<script>
export default {
    middleware: 'guest',
    data() {
        return {
            form: {
                email: '',
                password: ''
            }
        }
    },
    methods: {
        async submit() {
            await this.$auth.login({
                data: this.form
            });

            // this.$router.push({
            //     path: '/'
            // });
        }
    }
}
</script>

<style lang="css">
</style>
