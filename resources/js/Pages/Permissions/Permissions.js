import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import Layout from '@/Layouts/Layout.vue'
import EasyDataTable from 'vue3-easy-data-table'
import 'vue3-easy-data-table/dist/style.css'
import { toast } from 'vue3-toastify'

export default {
  name: 'PermissionsComponent',
  components: {
    Layout,
    EasyDataTable
  },
  data() {
    return {
      permissions: [],
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Permission Name', value: 'name' },
        { text: 'Actions', value: 'operation' },
      ],

    newPermission: {
        name: '',
        errors: {},
      },

    // const editingId = ref(null)

    editForm: {
        name: '',
        errors: {},
      },
       editingId: null,
        }
  },
  mounted() {
    this.fetchPermissions()
  },
methods: {
     fetchPermissions () {
      axios.get('/permissions')
        .then(response => {
          this.permissions = response.data.permissions || []
        })
        .catch(error => {
          toast.error(xhr.responseJSON.message || 'failed.');
          console.error('Fetch failed:', error)
        })
    },

     addPermission ()  {
      axios.post('/permissions', {
        name: this.newPermission.name,
      })
        .then(() => {
          toast.success('permission created!')
          this.newPermission.name = ''
          this.newPermission.errors = {}
          this.fetchPermissions()
        })
        .catch(error => {
          if (error.response?.status === 422) {
            this.newPermission.errors = error.response.data.errors
          } else {
            console.error('Add failed:', error)
          }
        })
    },

     startEdit  (item) {
      this.editingId = item.id
      this.editForm.name = item.name
      this.editForm.errors = {}
    },

     cancelEdit () {
      this.editingId = null
      this.editForm.name = ''
      this.editForm.errors = {}
    },

     updatePermission (id){
      axios.put(`/permissions/${id}`, {
        name: this.editForm.name,
      })
        .then(() => {
          this.fetchPermissions()
          this.cancelEdit()
        })
        .catch(error => {
          if (error.response?.status === 422) {
            this.editForm.errors = error.response.data.errors
          } else {
            console.error('Update failed:', error)
          }
        })
    },

     deletePermission (id) {
      if (confirm('Are you sure you want to delete this permission?')) {
        axios.delete(`/permissions/${id}`)
          .then(() => {
            this.fetchPermissions()
          })
          .catch(error => {
            console.error('Delete failed:', error)
          })
      }
    },

    // onMounted(() => {
    //   fetchPermissions()
    // })

    // return {
    //   permissions,
    //   headers,
    //   newPermission,
    //   editingId,
    //   editForm,
    //   fetchPermissions,
    //   addPermission,
    //   startEdit,
    //   cancelEdit,
    //   updatePermission,
    //   deletePermission,
    // }
  }
}
