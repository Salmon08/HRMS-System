import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import { toast } from 'vue3-toastify'
import Swal from 'sweetalert2';

export default {
  props: {
    employee: {
      type: Object,
      default: () => ({
        id: null,
        name: '',
        email: '',
        phone: '',
        department: '',
        position: '',
        join_date: '',
        status: '',
        role_name: '',
      }),
    },
    roles: Array,
  },
  data() {
    const emp = this.employee || {};
  return {
    form: {
      name: emp.name || '',
      email: emp.email || '',
      phone: emp.phone || '',
      department: emp.department || '',
      position: emp.position || '',
      join_date: emp.join_date || '',
      status: emp.status || '',
      role: emp.role_name || '',
      password: '',
    },
    errors: {},
  };
  },
  methods: {
    submit() {
      const url = this.employee && this.employee.id
        ? `/employees/${this.employee.id}`
        : '/employees';

      const method = this.employee && this.employee.id ? 'put' : 'post';
       Swal.fire({
                title: 'Are you sure?',
                text: "You want to submit!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                if (result.isConfirmed) {

      axios[method](url, this.form)
        .then(() => {
        //   alert('Employee saved successfully!');
        Swal.fire('Submitted!', 'Employee created successfully.', 'success')
        toast.success("Employee Added successfully!");
          window.location.href = '/employees'; // redirect to list page
        })
        .catch((error) => {
          if (error.response && error.response.data.errors) {
            this.errors = error.response.data.errors;
          } else {
            // alert('Something went wrong.');
            toast.error(xhr.responseJSON.message || 'failed.');
            console.error(error);
          }
        });
        }
            })
    },
     cancel() {
      Inertia.visit('/employees')
    }

  },

};
