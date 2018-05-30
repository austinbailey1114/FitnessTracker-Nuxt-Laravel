<template>
    <div class="">
        <div v-if="!authenticated">
            <nuxt-link :to="{ name: 'auth-signin' }" class="button is-text">
                Sign in
            </nuxt-link>
            <nuxt-link :to="{ name: 'auth-signup' }" class="button is-text">
                Sign up
            </nuxt-link>
        </div>
        <div v-else>
            <a @click.prevent="logout" class="button is-text">Sign out</a>
            {{ authenticated }}
            {{ user }}
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  components: {

  },
  methods: {
      logout() {
          this.$auth.logout();
      }
  }
}
</script>

<style>

</style>
