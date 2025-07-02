import axios from 'axios'
import Layout from '@/Layouts/Layout.vue'

import 'vue3-easy-data-table/dist/style.css'
import { toast } from 'vue3-toastify'

export default {
    layout: Layout,
  data() {
    return {
      form: {
        email: '',
      },
    };
  },
  methods: {
    submit() {
      axios.post('/forget-password' , {
        email: this.form.email,
      })
        .then(() => {
          toast.success('Password reset link sent to your email!');
          this.form.email = '';
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            toast.error('Please enter a valid email address.');
          } else {
            toast.error('An error occurred while sending the reset link.');
          }
        }
        );

    },
  },
};
