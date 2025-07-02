<template>
    <Layout>
        <h2 class="text-2xl font-bold mb-4">Permissions Management</h2>

        <form @submit.prevent="addPermission" class="mb-6">
            <div class="flex gap-3 items-start">
                <input v-model="newPermission.name" placeholder="New Permission Name"
                    class="border px-4 py-2 rounded w-[30%]" /><span class="text-red-500">*</span>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add
                </button>
            </div>
            <div v-if="newPermission.errors.name" class="text-red-500 mt-1">
                {{ newPermission.errors.name }}
            </div>
        </form>

        <EasyDataTable :headers="headers" :items="permissions" :search-field="'name'">
            <template #item-operation="permission">
                <div v-if="editingId === permission.id" class="flex gap-2">
                    <input v-model="editForm.name" class="border px-2 py-1 rounded w-full" />
                    <div v-if="editForm.errors.name" class="text-red-500 text-sm">
                        {{ editForm.errors.name }}
                    </div>
                    <button class="text-green-600" @click="updatePermission(permission.id)">
                        Update
                    </button>
                    <button class="text-gray-500" @click="cancelEdit">
                        Cancel
                    </button>
                </div>
                <div v-else class="flex gap-2">
                    <span class="flex-1">{{ permission.name }}</span>
                    <button class="text-blue-600" @click="startEdit(permission)">
                        Edit
                    </button>
                    <button class="text-red-600" @click="deletePermission(permission.id)">
                        Delete
                    </button>
                </div>
            </template>
        </EasyDataTable>
    </Layout>
</template>
<script src="./Permissions.js"></script>
