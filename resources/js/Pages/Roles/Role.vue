<template>
  <Layout>
    <h2 class="text-2xl font-bold mb-4">Role Management</h2>

    <!-- Add Role Form -->
<form @submit.prevent="addRole" class="mb-6 flex flex-col w-full  justify-center">
  <div class="flex items-center gap-3 w-3/4">
    <div class="w-[33%]">
            <label class=" mb-2 font-semibold">Role Name<span class="text-red-500">*</span></label>
            <input v-model="newRole.name" type="text"
            class="w-full  border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <div v-if="newRole.errors.name" class="text-red-500 text-sm">
                {{ newRole.errors.name }}
        </div>
    </div>

    <!-- Custom Permissions Dropdown -->
    <div class="relative">
        <label class="inline mb-2 font-semibold">Permissions<span class="text-red-500">*</span></label>
      <button
        type="button"
        @click="showPermissionDropdown = !showPermissionDropdown"
        class="block border px-4 py-2 rounded bg-white w-48 text-left"
      >
        <span v-if="newRole.permissions.length">
          {{ allPermissions.filter(p => newRole.permissions.includes(p.id)).map(p => p.name).join(', ') }}
        </span>
        <span v-else class="text-gray-400">Select Permissions</span>
      </button>
      <div
        v-if="showPermissionDropdown"
        class="absolute z-10 bg-white border rounded shadow-md mt-1 w-48 max-h-48 overflow-y-auto"
      >
        <div
          v-for="permission in allPermissions"
          :key="permission.id"
          class="px-3 py-2 hover:bg-gray-100 flex items-center"
        >
          <input
            type="checkbox"
            :id="'perm-' + permission.id"
            :value="permission.id"
            v-model="newRole.permissions"
            class="mr-2"
          />
          <label :for="'perm-' + permission.id">{{ permission.name }}</label>
        </div>
      </div>
        <div v-if="newRole.errors.permissions" class="text-red-500 text-sm">
            {{ newRole.errors.permissions[0] }}
        </div>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 mt-6 rounded hover:bg-blue-700">
      Add Role
    </button>
  </div>
</form>

    <!-- Data Table -->
    <EasyDataTable :headers="headers" :items="roles" row-key="id" :search-field="'name'">
  <template #item-permissions="role">

    <div class="text-sm">
      <span
        v-for="p in role.permissions"
        :key="p.id"
        class="inline-block bg-gray-200 rounded px-2 py-1 mr-1 mb-1"
      >
        {{ p.name }}
      </span>
    </div>
  </template>

  <template #item-operation="role">
  <div v-if="editingId === role.id" class="flex flex-col gap-2">
    <input v-model="editForm.name" class="border px-2 py-1 rounded" />

    <!-- Custom Permissions Dropdown for Edit -->
    <div class="relative">
      <button
        type="button"
        @click="showEditPermissionDropdown = !showEditPermissionDropdown"
        class="block border px-4 py-2 rounded bg-white w-48 text-left"
      >
        <span v-if="editForm.permissions.length">
          {{ allPermissions.filter(p => editForm.permissions.includes(p.id)).map(p => p.name).join(', ') }}
        </span>
        <span v-else class="text-gray-400">Select Permissions</span>
      </button>
      <div
        v-if="showEditPermissionDropdown"
        class="absolute z-10 bg-white border rounded shadow-md mt-1 w-48 max-h-48 overflow-y-auto"
      >
        <div
          v-for="permission in allPermissions"
          :key="permission.id"
          class="px-3 py-2 hover:bg-gray-100 flex items-center"
        >
          <input
            type="checkbox"
            :id="'edit-perm-' + permission.id"
            :value="permission.id"
            v-model="editForm.permissions"
            class="mr-2"
          />
          <label :for="'edit-perm-' + permission.id">{{ permission.name }}</label>
        </div>
      </div>
    </div>

    <div class="flex gap-2 mt-1">
      <button class="text-green-600" @click="updateRole(role.id)">Update</button>
      <button class="text-gray-500" @click="cancelEdit">Cancel</button>
    </div>
  </div>
  <div v-else class="flex gap-2">
    <button class="text-blue-600" @click="startEdit(role)">Edit</button>
    <button class="text-red-600" @click="deleteRole(role.id)">Delete</button>
  </div>
</template>
</EasyDataTable>
  </Layout>
</template>
<script src="./Role.js"></script>
