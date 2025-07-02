import Layout from '@/Layouts/Layout.vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  layout: Layout,

  props: {
    user: Object,
  },

  data() {
    return {
      form: {
        name: this.user.name,
        email: this.user.email,
        phone: this.user.phone,
        department: this.user.department,
        position: this.user.position,
        join_date: this.user.join_date,
        password: '',
      },
      errors: {},
    }
  },

  methods: {
    updateProfile() {
      axios
        .put('/profile', this.form)
        .then(() => {
          toast.success('Profile updated successfully!')
        })
        .catch((error) => {
          if (error.response?.data?.errors) {
            this.errors = error.response.data.errors
          } else {
            toast.error('An error occurred.')
          }
        })
    },
  },
}
