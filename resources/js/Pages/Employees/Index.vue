<template>
    <div>
        <h2 class="text-2xl font-bold mb-4 text-center">Employee List</h2>

        <div class="mt-4 mb-1">
            <button @click="$inertia.visit('/employees/create')" class="bg-blue-600 text-white px-4 py-1 rounded">
                Add Employee
            </button>
        </div>

        <EasyDataTable :headers="headers" :items="employeeList" row-key="id">
            <template v-slot:item-operation="employee">
                <pre>{{ slotProps }}</pre>
                <div class="flex gap-2">
                    <button @click="edit(employee)" class="bg-blue-600 text-white px-3 py-1 rounded">
                        Edit
                    </button>
                    <button @click="remove(employee.id)" class="bg-red-600 text-white px-3 py-1 rounded">
                        Delete
                    </button>
                </div>
            </template>
        </EasyDataTable>

    </div>
</template>

<script src="./Index.js"></script>
