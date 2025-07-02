<template>
    <div class="max-w-5xl mx-auto px-6 py-10">
        <h2 class="text-3xl font-bold mb-8 text-center">
            {{ employee?.id ? 'Edit Employee' : 'Add Employee' }}
        </h2>

        <form @submit.prevent="submitForm" class="grid grid-cols-3 md:grid-cols-3 gap-6">
            <!-- Name -->
            <div>
                <label class="block mb-2 font-semibold">Name<span class="text-red-500">*</span></label>
                <input v-model="form.name" type="text"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2 font-semibold">Email<span class="text-red-500">*</span></label>
                <input v-model="form.email" type="email"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-2 font-semibold">Phone<span class="text-red-500">*</span></label>
                <input v-model="form.phone" type="text"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Department -->
            <div>
                <label class="block mb-2 font-semibold">Department<span class="text-red-500">*</span></label>
                <input v-model="form.department" type="text"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Position -->
            <div>
                <label class="block mb-2 font-semibold">Position<span class="text-red-500">*</span></label>
                <input v-model="form.position" type="text"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Join Date -->
            <div>
                <label class="block mb-2 font-semibold">Join Date<span class="text-red-500">*</span></label>
                <input v-model="form.join_date" type="date"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-2 font-semibold">Status<span class="text-red-500">*</span></label>
                <select v-model="form.status"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Role -->
            <div>
                <label class="block mb-2 font-semibold">Role<span class="text-red-500">*</span></label>
                <select v-model="form.role"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option disabled value="">-- Select Role --</option>
                    <option v-for="role in roles" :key="role.name" :value="role.name">
                        {{ role.name }}
                    </option>
                </select>
            </div>

            <!-- Password (only when adding) -->
            <div v-if="!employee?.id" class="md:col-span-2">
                <label class="block mb-2 font-semibold">Password<span class="text-red-500">*</span></label>
                <input v-model="form.password" type="password"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex justify-end mt-4 gap-4">
                <button type="button" @click="cancel()"
                    class="border px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit" @click.prevent="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    {{ employee?.id ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</template>




<script src="./Form.js"></script>
