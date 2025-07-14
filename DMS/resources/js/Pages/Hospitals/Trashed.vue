<template>
  <AppLayout>
    <div class="custom-title mb-4">Trashed Hospitals</div>
    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">Hospital ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Branch</th>
          <th class="text-left">Type</th>
          <th class="text-left">Deleted At</th>
          <th class="text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="hospital in hospitals" :key="hospital.id">
          <td>{{ hospital.id }}</td>
          <td>{{ hospital.name }}</td>
          <td>{{ hospital.branch }}</td>
          <td>{{ hospital.type }}</td>
          <td>{{ dayjs(hospital.deleted_at).format('MM/DD/YYYY') }}</td>
          <td>
            <v-btn
              color="success"
              variant="text"
              size="small"
              @click="openRestoreDialog(hospital)"
            >
              Restore
            </v-btn>
            <v-btn
              color="error"
              variant="text"
              size="small"
              @click="openForceDeleteDialog(hospital)"
            >
              Delete Permanently
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>
    <Link :href="route('hospitals.index')">
      <v-btn class="mt-4" color="primary" variant="flat">Back to List</v-btn>
    </Link>

    <v-dialog v-model="confirmRestoreDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Restore</v-card-title>
        <v-card-text>
          Are you sure you want to restore
          <strong v-if="hospitalToRestore">{{ hospitalToRestore.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmRestoreDialog = false">Cancel</v-btn>
          <v-btn color="success" variant="flat" @click="restoreHospital">Restore</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="confirmForceDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Permanent Delete</v-card-title>
        <v-card-text>
          Are you sure you want to permanently delete
          <strong v-if="hospitalToForceDelete">{{ hospitalToForceDelete.name }}</strong>?
          <br>
          <span class="text-error">This action cannot be undone.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmForceDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="forceDeleteHospital">Delete Permanently</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  hospitals: Array
})

const confirmRestoreDialog = ref(false)
const hospitalToRestore = ref(null)
const confirmForceDeleteDialog = ref(false)
const hospitalToForceDelete = ref(null)

function openRestoreDialog(hospital) {
  hospitalToRestore.value = hospital
  confirmRestoreDialog.value = true
}

function restoreHospital() {
  if (hospitalToRestore.value) {
    router.post(route('hospitals.restore', hospitalToRestore.value.id))
    confirmRestoreDialog.value = false
    hospitalToRestore.value = null
  }
}

function openForceDeleteDialog(hospital) {
  hospitalToForceDelete.value = hospital
  confirmForceDeleteDialog.value = true
}

function forceDeleteHospital() {
  if (hospitalToForceDelete.value) {
    router.delete(route('hospitals.forceDelete', hospitalToForceDelete.value.id))
    confirmForceDeleteDialog.value = false
    hospitalToForceDelete.value = null
  }
}
</script>