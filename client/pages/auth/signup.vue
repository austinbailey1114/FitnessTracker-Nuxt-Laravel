<template lang="html">
    <div class="">
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
                    <p class="auth-prompt">Name</p>
                    <input type="text" name="name" v-model="form.name" class="auth-input" :class="{ 'is-danger': errors.name }" placeholder="John Smith">
                    <p class="help is-danger" v-if="errors.name">
                        {{ errors.name[0] }}
                    </p>
                    <div class="auth-submit-container">
                        <button class="auth-submit">Sign Up</button>
                    </div>
                    <div class="auth-bottom-link">
                        <nuxt-link :to="{ name: 'index' }" class="auth-link">
                            <u>I have an account</u>
                        </nuxt-link>
                    </div>
                </form>
            </div>
            <div class="auth-right">
                <div class="background-signup background">

                </div>
                <div class="garble garble-signup">
                    <p class="auth-info">Track Nutrition, Weight Lifting, and Bodyweight with Fitness Tracker</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
                name: ''
            }
        }
    },
    methods: {
        async submit() {
            await this.$axios.post('register', this.form)

            await this.$auth.login({
                data: this.form
            })

            this.$router.push({
                name: 'index'
            })
        }
    }
}
</script>

<style lang="css">
</style>
