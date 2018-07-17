<template lang="html">
    <div>
        <div class="top">
            <div class="top-left inline">
                <div class="title inline">
                    <img class="image" src="hugeIcon.png" height="60" width="60">
                    <p class="header" align="center">Dashboard</p>
                </div>
                <div class="links-container inline">
                    <a href="https://github.com/austinbailey1114/FitnessTracker-nuxt-laravel" class="link">GitHub</a>
                    <a href="https://austinmbailey.com" class="link">About</a>
                </div>
            </div>
            <div class="dropdown inline">
                <a @click.prevent="logout" class="link sign-out">Sign out</a>
            </div>
        </div>
        <div class="dashboard">
            <lift-component></lift-component>
            <no-ssr>
                <food-component></food-component>
            </no-ssr>
            <bodyweight-component></bodyweight-component>
        </div>
    </div>
</template>

<script>
import LiftComponent from '@/components/partials/LiftComponent'
import FoodComponent from '@/components/partials/FoodComponent'
import BodyweightComponent from '@/components/partials/BodyweightComponent'

export default {
    middleware: 'auth',
    components: {
        'lift-component': LiftComponent,
        'food-component': FoodComponent,
        'bodyweight-component': BodyweightComponent
    },
    data: function() {
        return {
            name: 'Austin Bailey',
        }
    },
    methods: {
        logout() {
            this.$auth.logout()
        }
    }
}
</script>

<style lang="css">

</style>
