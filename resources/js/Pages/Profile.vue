<template>
  <Layout>
    <h2 class="text-2xl font-bold mb-4">My Profile</h2>

    <form @submit.prevent="updateProfile" class="space-y-4 max-w-lg">
      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input
          v-model="form.name"
          type="text"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</p>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.email" class="text-red-600 text-sm">{{ errors.email }}</p>
      </div>

      <!-- Phone -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Phone</label>
        <input
          v-model="form.phone"
          type="phone"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.phone" class="text-red-600 text-sm">{{ errors.phone }}</p>
      </div>

      <!-- Department -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Department</label>
        <input
          v-model="form.department"
          type="department"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.department" class="text-red-600 text-sm">{{ errors.department }}</p>
      </div>

      <!-- Position -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Position</label>
        <input
          v-model="form.position"
          type="position"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.position" class="text-red-600 text-sm">{{ errors.position }}</p>
      </div>

      <!-- Join Date -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Join Date</label>
        <input
          v-model="form.join_date"
          type="join_date"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.join_date" class="text-red-600 text-sm">{{ errors.join_date }}</p>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700">New Password</label>
        <input
          v-model="form.password"
          type="password"
          class="mt-1 block w-full border rounded px-3 py-2 border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <p v-if="errors.password" class="text-red-600 text-sm">{{ errors.password }}</p>
      </div>

      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Update Profile
      </button>
    </form>
  </Layout>
</template>

<script src="./Profile.js"></script>
