<template>
    <div>
        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>
        <!-- <h2>Reset Password</h2> -->
        <form v-if="showForm" @submit.prevent="submit">
            <!-- <input type="email" v-model="form.email" placeholder="Email" /><br> -->
            <input type="password" v-model="form.password" placeholder="New Password" /><br>
            <input type="password" v-model="form.password_confirmation" placeholder="Confirm Password" /><br>
            <button class="bg-blue-500 m-2 px-4 py-2 rounded text-white">Reset Password</button>
        </form>
    </div>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';

export default {

    props: ['token', 'uuid', 'error', 'showForm'],
    data() {
        return {
            form: {
                uuid: this.uuid,
                token: this.token,
                // email: '',
                password: '',
                password_confirmation: '',
            },
        };
    },
    methods: {
        submit() {
            axios.post('/reset-password' , {
            uuid: this.form.uuid,
            token: this.form.token,
            password: this.form.password,
            password_confirmation: this.form.password_confirmation,
      })
        .then(() => {
          toast.success('Password reset successfully!');
          this.form.email = '';
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            toast.error('Failed to reset password.');
          } else {
            toast.error('An error occurred while sending the reset password.');
          }
        }
        );

        },
    },
};
</script>
