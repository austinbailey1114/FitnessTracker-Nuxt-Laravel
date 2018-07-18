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
body {
    font-family: 'Nunito', serif !important;
    margin:0;
    padding: 0;
    background-color: #292C3D;
    color: #EAEDEE;
}

@media screen and (max-width: 1280px) {
    body {
        transform: scale(0.8);
        transform-origin: 0 0;
        width: 125%;
    }
}

@media screen and (max-width: 1024px) {
    body {
        transform: scale(0.7);
        transform-origin: 0 0;
        width: 142.857%;
    }

}

@media screen and (max-width: 850px) {
    .progress-bars {
        width: 53%;
    }

}

@media screen and (max-width: 768px) {
    body {
        transform: scale(0.5);
        transform-origin: 0 0;
        width: 200%;
    }

    .progress-bars {
        width: 57%;
    }

}

@media screen and (max-width: 600px) {
    body {
        transform: scale(0.4);
        transform-origin: 0 0;
        width: 250%;
    }

    .progress-bars {
        width: 57%;
    }

}

@media screen and (max-width: 480px) {
    body {
        transform: scale(0.6);
        transform-origin: 0 0;
        width: 166.666%;
    }

    .container-child {
        display: block;
        position: relative;
        margin-bottom: 20px;
        height: 100%;
    }

    .container {
        position: relative;
        height: none;
        display: block;
        margin-bottom: 400px;
    }

    .new-lift {
        float: none;
        width: 100%;
    }

    .lift-graph {
        float: none;
        width: 100%;
    }

    .top-left {
        width: 80%;
    }

    .food-total {
        float: none;
        width: 100%;
        display: block;
    }

    .food-stuff {
        float: none;
        width: 100%;
    }

    .bodyweight-graph {
        float: none;
        width: 100%;
    }

    .new-weight {
        float: none;
        width: 100%;
    }

}

</style>
