import axios from 'axios'
import Layout from '@/Layouts/Layout.vue'
import EasyDataTable from 'vue3-easy-data-table'
import 'vue3-easy-data-table/dist/style.css'
import Swal from 'sweetalert2'
import { toast } from 'vue3-toastify'

export default {
  layout: Layout,
  components: {
    EasyDataTable,
  },
  props: {
    employees: Array,
    roles: Array,
  },
  data() {
    return {
      employeeList: this.employees,
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' },
        { text: 'Department', value: 'department' },
        { text: 'Position', value: 'position' },
        { text: 'Join Date', value: 'join_date' },
        { text: 'Status', value: 'status' },
        { text: 'Role', value: 'role_name' },
        { text: 'Actions', value: 'operation', sortable: false },
      ],
    }
  },
  methods: {
    edit(employee) {
      this.$inertia.visit(`/employees/${employee.id}/edit`)
    },
    remove(id) {
      Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
        axios
          .delete(`/employees/${id}`)
          .then(() => {
            toast.success('Employee deleted successfully!')
            this.employeeList = this.employeeList.filter(emp => emp.id !== id)
        })
          .catch((err) => {
            alert('Delete failed')
            console.error(err)
          })
        }
    })

    },
    fetchEmployees() {
      axios
        .get('/employees')
        .then((res) => {
          this.employeeList = Array.isArray(res.data.employees) ? res.data.employees : []
        })
        .catch((err) => {
          this.employeeList = []
          console.error('Failed to fetch employees', err)
        })
    },
  },
}
