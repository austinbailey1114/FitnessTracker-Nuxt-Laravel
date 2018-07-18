<template>
    <div class="">
        <div class="auth-parent">
            <div class="auth-container">
                <div class="auth-left">
                    <div class="auth-image-container">
                        <img src="/hugeIcon.png" height="75" class="auth-image">
                    </div>
                    <form @submit.prevent="submit()">
                        <p class="auth-prompt">Email</p>
                        <input type="email" name="email" v-model="form.email" class="auth-input" :class="{ 'is-danger': errors.email }" placeholder="example@example.com">
                        <p class="help is-danger" v-if="errors.email">
                            {{ errors.email[0] }}
                        </p>
                        <p class="auth-prompt">Password</p>
                        <input type="password" name="password" v-model="form.password" class="auth-input" :class="{ 'is-danger': errors.password }" placeholder="****">
                        <p class="help is-danger" v-if="errors.password">
                            {{ errors.password[0] }}
                        </p>
                        <div class="auth-submit-container">
                            <button class="auth-submit">Login</button>
                        </div>
                        <div class="auth-bottom-link">
                            <nuxt-link :to="{ name: 'auth-signup' }" class="auth-link">
                                <u>Create Account</u>
                            </nuxt-link>
                        </div>
                    </form>
                </div>
                <div class="auth-right">
                    <div class="background">

                    </div>
                    <div class="garble">
                        <p class="auth-info">Track Nutrition, Weight Lifting, and Bodyweight with Fitness Tracker</p>
                    </div>
                </div>
            </div>
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
        }
    }
}
</script>

<style>

</style>
