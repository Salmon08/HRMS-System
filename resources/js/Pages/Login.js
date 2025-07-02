import Layout from '@/Layouts/Layout.vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { auth, provider, signInWithPopup } from '@/firebase';

export default {
  layout: Layout,

  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: {}
    }
  },

  methods: {
    submit() {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

  axios.post('/login', {
    ...this.form,
    _token: token
  })
  .then(() => {
    toast.success('Login successful!')
    router.visit('/employees');
  })
  .catch((error) => {
    if (error.response) {
      this.errors = error.response.data.errors || {}
      toast.error(error.response.data.message || 'Login failed.')
    } else {
      toast.error('Network error. Please try again.')
    }
  })
},

loginWithGoogle() {
      signInWithPopup(auth, provider)
        .then(async (result) => {
          const user = result.user
          const token = await user.getIdToken()

          const csrfTokenEl = document.querySelector('meta[name="csrf-token"]')
          const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : ''

          axios
            .post('/firebase-login', {
              token: token,
              _token: csrfToken,
            })
            .then(() => {
              toast.success('Login successful!')
              router.visit('/employees')
            })
            .catch((error) => {
              if (error.response) {
                this.errors = error.response.data.errors || {}
                toast.error(error.response.data.message || 'Login failed.')
              } else {
                toast.error('Network error. Please try again.')
              }
            })
        })
        .catch((error) => {
          toast.error('Google login failed: ' + error.message)
        })
    },
  }
}
