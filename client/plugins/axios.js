export default function({ $axios, store }) {
    // onError 422
    $axios.onError(error => {
        if (error.response.status === 422) {
            store.dispatch('validation/setErrors', error.response.data.errors)
        }

        return Promise.reject(error)
    })
    // onRequest clear
    $axios.onRequest(() => {
        store.dispatch('validation/clearErrors')
    })
}
