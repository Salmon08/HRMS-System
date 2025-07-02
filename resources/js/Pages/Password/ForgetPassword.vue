<template>
  <div class="flex flex-col items-center justify-center h-screen">
    <h2>Forgot Password</h2>
    <form @submit.prevent="submit">
      <input type="email" v-model="form.email" placeholder="Enter your email" class="form-control" /><br>
      <button class="bg-blue-500 m-2 px-4 py-2 rounded text-white">Send Reset Link</button>
    </form>
  </div>
</template>
<script src="./ForgetPassword.js"></script>
