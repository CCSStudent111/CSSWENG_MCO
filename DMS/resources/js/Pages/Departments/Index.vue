<template>
  <AppLayout>
    <div class="custom-title mb-4">Departments</div>
    <div class="mb-4 d-flex justify-space-between align-center">
      <v-btn-toggle v-model="filter" mandatory>
        <v-btn value="all" color="primary" variant="outlined">All</v-btn>
        <v-btn value="active" color="primary" variant="outlined">Active</v-btn>
        <v-btn value="trashed" color="primary" variant="outlined">Trashed</v-btn>
      </v-btn-toggle>
      <Link :href="route('departments.create')">
        <v-btn color="primary" variant="flat" class="ml-4">
          <v-icon left>mdi-plus</v-icon>
          Add Department
        </v-btn>
      </Link>
    </div>
    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Status</th>
          <th class="text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="dept in filteredDepartments" :key="dept.id">
          <td>{{ dept.id }}</td>
          <td>{{ dept.name }}</td>
          <td>
            <v-chip :color="dept.deleted_at ? 'error' : 'success'" size="small" variant="tonal">
              {{ dept.deleted_at ? 'Trashed' : 'Active' }}
            </v-chip>
          </td>
          <td>
            <Link :href="route('departments.edit', dept.id)">
              <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
            </Link>
            <v-btn
              icon
              size="small"
              color="error"
              variant="text"
              aria-label="Delete"
              @click="openDeleteDialog(dept)"
              v-if="!dept.deleted_at"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
            <v-btn
              icon
              size="small"
              color="success"
              variant="text"
              aria-label="Restore"
              @click="openRestoreDialog(dept)"
              v-if="dept.deleted_at"
            >
              <v-icon>mdi-restore</v-icon>
            </v-btn>
            <v-btn
              icon
              size="small"
              color="error"
              variant="text"
              aria-label="Force Delete"
              @click="openForceDeleteDialog(dept)"
              v-if="dept.deleted_at"
            >
              <v-icon>mdi-delete-forever</v-icon>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <!-- Delete Confirmation Dialog -->
    <v-dialog v-model="confirmDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Delete</v-card-title>
        <v-card-text>
          Are you sure you want to delete
          <strong v-if="departmentToDelete">{{ departmentToDelete.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="deleteDepartment">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Restore Confirmation Dialog -->
    <v-dialog v-model="confirmRestoreDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Restore</v-card-title>
        <v-card-text>
          Are you sure you want to restore
          <strong v-if="departmentToRestore">{{ departmentToRestore.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmRestoreDialog = false">Cancel</v-btn>
          <v-btn color="success" variant="flat" @click="restoreDepartment">Restore</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Force Delete Confirmation Dialog -->
    <v-dialog v-model="confirmForceDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Permanent Delete</v-card-title>
        <v-card-text>
          Are you sure you want to permanently delete
          <strong v-if="departmentToForceDelete">{{ departmentToForceDelete.name }}</strong>?
          <br>
          <span class="text-error">This action cannot be undone.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmForceDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="forceDeleteDepartment">Delete Permanently</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  departments: Array
})

const filter = ref('all')

const filteredDepartments = computed(() => {
  if (filter.value === 'active') {
    return props.departments.filter(d => !d.deleted_at)
  }
  if (filter.value === 'trashed') {
    return props.departments.filter(d => d.deleted_at)
  }
  return props.departments
})

const confirmDeleteDialog = ref(false)
const departmentToDelete = ref(null)
const confirmRestoreDialog = ref(false)
const departmentToRestore = ref(null)
const confirmForceDeleteDialog = ref(false)
const departmentToForceDelete = ref(null)

function openDeleteDialog(dept) {
  departmentToDelete.value = dept
  confirmDeleteDialog.value = true
}

function deleteDepartment() {
  if (departmentToDelete.value) {
    router.delete(route('departments.destroy', departmentToDelete.value.id))
    confirmDeleteDialog.value = false
    departmentToDelete.value = null
  }
}

function openRestoreDialog(dept) {
  departmentToRestore.value = dept
  confirmRestoreDialog.value = true
}

function restoreDepartment() {
  if (departmentToRestore.value) {
    router.post(route('departments.restore', departmentToRestore.value.id))
    confirmRestoreDialog.value = false
    departmentToRestore.value = null
  }
}

function openForceDeleteDialog(dept) {
  departmentToForceDelete.value = dept
  confirmForceDeleteDialog.value = true
}

function forceDeleteDepartment() {
  if (departmentToForceDelete.value) {
    router.delete(route('departments.forceDelete', departmentToForceDelete.value.id))
    confirmForceDeleteDialog.value = false
    departmentToForceDelete.value = null
  }
}
</script>