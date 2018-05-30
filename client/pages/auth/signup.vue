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
                    <p>Name</p>
                    <input type="text" name="name" v-model="form.name" class="input is-large" :class="{ 'is-danger': errors.name }">
                    <p class="help is-danger" v-if="errors.name">
                        {{ errors.name[0] }}
                    </p>
                    <button class="button">Login</button>
                </form>
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
