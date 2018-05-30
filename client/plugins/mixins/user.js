import Vue from 'vue'
import { mapGetters } from 'vuex'

const User = {
    install(Vue, options) {
        Vue.mixin({
            computed: {
                ...mapGetters({
                    user: 'auth/user',
                    authenticated: 'auth/authenticated'
                })
            }
        })
    }
}

Vue.use(User)
