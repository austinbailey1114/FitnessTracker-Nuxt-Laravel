<template lang="html">
    <div>
        <link rel="stylesheet" href="#">
        <div class="top">
            <div class="top-left inline">
                <div class="title inline">
                    <img class="image" src="hugeIcon.png" height="70" width="70">
                    <p class="header" align="center">Dashboard</p>
                </div>
                <div class="links-container inline">
                    <a href="https://github.com/austinbailey1114/LiftAppSite" class="link">About</a>
                    <a href="https://github.com/austinbailey1114/iOS" class="link">The App</a>
                </div>
            </div>
            <div class="dropdown inline">
                <a @click.prevent="logout" class="link">Sign out</a>
            </div>
        </div>
        <div class="dashboard">
            <lift-component></lift-component>
            <food-component></food-component>
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

    }
}
</script>

<style lang="css">

</style>
