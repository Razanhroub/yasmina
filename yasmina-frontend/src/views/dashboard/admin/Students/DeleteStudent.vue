<td>
  <button @click="deleteStudent(student.student_id)">Delete</button>
</td>

<script setup>
import Swal from 'sweetalert2';
import api from '../../../../axios.js';
const deleteStudent = async (student) => {
  const confirmResult = await Swal.fire({
    title: `Are you sure?`,
    text: `This will soft delete ${student.name} and all related records.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (confirmResult.isConfirmed) {
    try {
      await api.delete(`/admin/students/user/${student.student_id}`); // <-- user ID
      Swal.fire({
        icon: 'success',
        title: 'Deleted',
        text: `${student.name} has been deleted`,
        timer: 2000,
        showConfirmButton: false
      });

      fetchStudents();
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || 'Failed to delete student'
      });
    }
  }
};
</script>
