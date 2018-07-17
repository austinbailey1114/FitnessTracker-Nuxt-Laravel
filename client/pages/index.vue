<template>
    <div class="">
        <div v-if="!authenticated">
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
            <nuxt-link :to="{ name: 'auth-signup' }" class="button is-text">
                Sign up
            </nuxt-link>

        </div>
        <div v-else>
            <a @click.prevent="logout" class="button is-text">Sign out</a>
            {{ authenticated }}
            {{ user }}
            <nuxt-link :to="{ name: 'dashboard' }" class="button is-text">
                Dashboard
            </nuxt-link>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

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

<style>

</style>
