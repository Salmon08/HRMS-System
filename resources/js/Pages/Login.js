import Layout from '@/Layouts/Layout.vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

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
    window.location.href = '/employees'
  })
  .catch((error) => {
    if (error.response) {
      this.errors = error.response.data.errors || {}
      toast.error(error.response.data.message || 'Login failed.')
    } else {
      toast.error('Network error. Please try again.')
    }
  })
}
  }
}
