<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">HRMS
                <span v-if="userRole" class="ml-2 text-sm text-gray-600 font-normal">
                    ({{ userRole }})
                </span>
            </h1>
            <div class="space-x-4">
                <Link v-if="hasRole(['admin', 'HR', 'manager'])" href="/dashboard"
                    class="text-dark-800 hover:underline">Dashboard</Link>
                <Link v-if="hasRole(['admin', 'HR', 'manager'])" href="/employees"
                    class="text-dark-800 hover:underline">Employees</Link>
                <Link v-if="hasRole(['admin', 'HR'])" href="/leaves" class="text-dark-800 hover:underline">Leaves</Link>
                <Link v-if="hasRole(['admin'])" href="/roles" class="text-dark-800 hover:underline">Roles</Link>
                <Link v-if="hasRole(['admin'])" href="/permissions" class="text-dark-800 hover:underline">Permissions
                </Link>
                <Link href="/profile" class="mr-4 text-dark-800 hover:underline">
                        Profile
                        </Link>
                <Link @click="logout()" as="button" class="text-red-600 hover:underline">Logout</Link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white text-center text-dark-800 p-4 border-t">
            &copy; {{ new Date().getFullYear() }} HRMS System
        </footer>
    </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

export default {
    components: { Link },

    computed: {
        user() {
            return this.$page.props.auth.user
        },
        userRole() {
            return this.user?.roles?.[0] || null  // show first role
        }
    },

    methods: {
        hasRole(roles) {
            return this.user && this.user.roles && roles.some(role => this.user.roles.includes(role))
        },

        logout() {
            Swal.fire({
                title: 'Are you sure you want to logout?',
                text: "You will need to log in again to access the dashboard.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    router.post('/logout', {}, {
                        onSuccess: () => {
                            window.location.reload()
                        }
                    })
                }
            })
        }
    }
}
</script>
