<template lang="html">
    <div>
        <!-- https://dribbble.com/shots/4549955-Daily-18 -->
        <link rel="stylesheet" href="#">
        <div class="top">
            <div class="top-left inline">
                <div class="title inline">
                    <img class="image" src="" height="40" width="40">
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
import { mapGetters } from 'vuex'
import { mapMutations } from 'vuex'
import LiftComponent from '@/components/partials/LiftComponent'
import FoodComponent from '@/components/partials/FoodComponent'
import BodyweightComponent from '@/components/partials/BodyweightComponent'

export default {
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
    created: function() {
        if (!this.getLoggedIn()) {
            this.$router.push({ path: '/login' })
        }
    },
    methods: {
        logout() {
            this.setLoggedIn(false);
            this.setKey(null);
            this.setId(null);
            this.$router.push({ path: '/login' });
        },
        ...mapGetters([
            'getLoggedIn'
        ]),
        ...mapMutations([
            'setKey',
            'setId',
            'setLoggedIn',
        ]),
    }
}
</script>

<style lang="css">

</style>
