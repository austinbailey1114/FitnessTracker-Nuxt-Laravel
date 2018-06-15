module.exports = {
  /*
  ** Headers of the page
  */
  head: {
    title: 'FitnessTracker',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'FitnessTracker' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto:200,300,400,500' }
      //{ rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css' }
    ]
  },
  css: [
      //'css/mystyles.scss',
      'css/index.scss'
  ],
  /*
  ** Customize the progress bar color
  */
  loading: { color: '#3B8070' },

  modules: [
      '@nuxtjs/axios',
      '@nuxtjs/auth'
  ],

  router: {
      middleware: [
          'clearValidationErrors'
      ]
  },

  plugins: [
      './plugins/mixins/user',
      './plugins/mixins/validation',
      './plugins/axios'
  ],

  axios: {
      baseURL: 'http://localhost:8000/api'
  },

  auth: {
      endpoints: {
          login: {
              url: 'login', method: 'post', propertyName: 'meta.token'
          },
          user: {
              url: 'me', method: 'get', propertyName: 'data'
          },
          logout: {
              url: 'logout', method: 'post'
          }
      },
      redirect: {
          login: '/auth/signin',
          home: '/'
      }
  },
  /*
  ** Build configuration
  */
  build: {
    /*
    ** Run ESLint on save
    */
    extend (config, { isDev, isClient }) {
      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
