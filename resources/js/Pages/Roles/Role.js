import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import Layout from '@/Layouts/Layout.vue'
import EasyDataTable from 'vue3-easy-data-table'
import 'vue3-easy-data-table/dist/style.css'

export default {
  name: 'RolesComponent',
  components: {
    Layout,
    EasyDataTable,
  },
//   props: {
//     roles: Array,
//     permissions: Array,
//   },
  data() {
    return {
      roles: [],
      allPermissions: [],
      editingId: null,
      showPermissionDropdown: false,
      showEditPermissionDropdown: false,
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Role Name', value: 'name' },
        { text: 'Permissions', value: 'permissions' },
        { text: 'Actions', value: 'operation', sortable: false },
      ],
      newRole: ({
        name: '',
        permissions: [],
        errors: {},
      }),
      editForm: ({
        name: '',
        permissions: [],
        errors: {},
      }),
    }
  },
  methods: {
    addRole() {
      axios
        .post('/roles', {
          name: this.newRole.name,
          permissions: this.newRole.permissions,
        })
        .then(() => {
          this.newRole.name = ''
          this.newRole.permissions = []
          this.newRole.errors = {}
          this.fetchRoles()
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            this.newRole.errors = error.response.data.errors
          } else {
            console.error('Add failed:', error)
          }
        })
    },
    startEdit(item) {
      this.editingId = item.id
      this.editForm.name = item.name
      this.editForm.permissions = item.permissions.map(p => p.id)
      this.editForm.errors = {}
    },
    cancelEdit() {
      this.editingId = null
      this.showEditPermissionDropdown = false
      this.editForm.name = ''
      this.editForm.permissions = []
      this.editForm.errors = {}
    },
    updateRole(id) {
      axios
        .put(`/roles/${id}`, {
          name: this.editForm.name,
          permissions: this.editForm.permissions,
        })
        .then(() => {
          this.cancelEdit()
          this.fetchRoles()
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            this.editForm.errors = error.response.data.errors
          } else {
            console.error('Update failed:', error)
          }
        })
    },
    deleteRole(id) {
      if (confirm('Are you sure you want to delete this role?')) {
        axios
          .delete(`/roles/${id}`)
          .then(() => this.fetchRoles())
          .catch((error) => console.error('Delete failed:', error))
      }
    },
    fetchRoles() {
      axios
        .get('/roles')
        .then((response) => {
          this.roles = response.data.roles
          this.allPermissions = response.data.permissions

        })
        .catch((error) => {
          console.error('Fetch failed:', error)
        })
    },
    handleClickOutside(event) {
    const dropdown = this.$el.querySelector('.relative')
    if (dropdown && !dropdown.contains(event.target)) {
      this.showPermissionDropdown = false
    }
  },
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside)
    this.fetchRoles()
  },
  beforeUnmount() {
  document.removeEventListener('click', this.handleClickOutside)
},
}
