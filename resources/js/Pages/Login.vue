<template>
    <div class="max-w-md mx-auto p-4">
        <h2 class="text-xl font-semibold mb-4">Login</h2>

        <form @submit.prevent="submit" novalidate>
            <div class="mb-4">
                <label>Email <span class="text-red-500">*</span></label>
                <input v-model="form.email" type="email" class="w-full border rounded p-2" required />
                <div v-if="errors.email" class="text-red-500 text-sm">{{ errors.email[0] }}</div>
            </div>

            <div class="mb-1">
                <label>Password <span class="text-red-500">*</span></label>
                <input v-model="form.password" type="password" class="w-full border rounded p-2" required />
                <div v-if="errors.password" class="text-red-500 text-sm">{{ errors.password[0] }}</div>
            </div>
            <div class="w-50 text-center text-sm mb-1 hover:underline text-blue-600">
                <a href="/forget-password">Forgot Password?</a>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">Login</button>
            <div class="p-1 mt-3 flex items-center gap-3 border border-gray-300 flex items-center justify-center">
                <img src="/signinwithgoogle.png" alt="Sign in with Google" class="w-6 h-6" />
                <button @click="loginWithGoogle" class="px-4 py-1  text-dark rounded hover:bg-blue-200">
                    Sign in with Google
                </button>
            </div>

        </form>
    </div>
</template>

<script src="./Login.js"></script>
