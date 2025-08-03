<template>
  <AppLayout>
    <div class="custom-title mb-4">Trashed Users</div>

    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">User ID</th>
          <th class="text-left">Username</th>
          <th class="text-left">Full Name</th>
          <th class="text-left">Email</th>
          <th class="text-left">Department</th>
          <th class="text-left">Deleted At</th>
          <th class="text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.username }}</td>
          <td>{{ user.first_name }} {{ user.last_name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.department?.name || '—' }}</td>
          <td>{{ dayjs(user.deleted_at).format('MM/DD/YYYY') }}</td>
          <td>
            <v-btn
              color="success"
              variant="text"
              size="small"
              @click="openRestoreDialog(user)"
            >
              Restore
            </v-btn>
            <v-btn
              color="error"
              variant="text"
              size="small"
              @click="openForceDeleteDialog(user)"
            >
              Delete Permanently
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <Link :href="route('users.index')">
      <v-btn class="mt-4" color="primary" variant="flat">Back to List</v-btn>
    </Link>

    <!-- Restore Dialog -->
    <v-dialog v-model="confirmRestoreDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Restore</v-card-title>
        <v-card-text>
          Are you sure you want to restore
          <strong v-if="userToRestore">{{ userToRestore.username }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmRestoreDialog = false">Cancel</v-btn>
          <v-btn color="success" variant="flat" @click="restoreUser">Restore</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Force Delete Dialog -->
    <v-dialog v-model="confirmForceDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Permanent Delete</v-card-title>
        <v-card-text>
          Are you sure you want to permanently delete
          <strong v-if="userToForceDelete">{{ userToForceDelete.username }}</strong>?
          <br />
          <span class="text-error">This action cannot be undone.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmForceDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="forceDeleteUser">Delete Permanently</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import dayjs from 'dayjs'

const props = defineProps({
  users: Array
})

const confirmRestoreDialog = ref(false)
const userToRestore = ref(null)
const confirmForceDeleteDialog = ref(false)
const userToForceDelete = ref(null)

function openRestoreDialog(user) {
  userToRestore.value = user
  confirmRestoreDialog.value = true
}

function restoreUser() {
  if (userToRestore.value) {
    router.post(route('users.restore', userToRestore.value.id))
    confirmRestoreDialog.value = false
    userToRestore.value = null
  }
}

function openForceDeleteDialog(user) {
  userToForceDelete.value = user
  confirmForceDeleteDialog.value = true
}

function forceDeleteUser() {
  if (userToForceDelete.value) {
    router.delete(route('users.forceDelete', userToForceDelete.value.id))
    confirmForceDeleteDialog.value = false
    userToForceDelete.value = null
  }
}
</script>

<style scoped>
.custom-title {
  font-size: 2rem;
  font-weight: bold;
}
</style>
