<template lang="html">
    <div class="">
        <div class="container has-text centered">
            <div class="column is-two-fifths centered">
                <form @submit.prevent="submit()">
                    <p>Email</p>
                    <input type="email" name="email" v-model="form.email" class="input is-large" :class="{ 'is-danger': errors.email }">
                    <p class="help is-danger" v-if="errors.email">
                        {{ errors.email[0] }}
                    </p>
                    <p>Password</p>
                    <input type="password" name="password" v-model="form.password" class="input is-large" :class="{ 'is-danger': errors.password }">
                    <p class="help is-danger" v-if="errors.password">
                        {{ errors.password[0] }}
                    </p>
                    <button class="button">Login</button>
                </form>
            </div>
        </div>
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
