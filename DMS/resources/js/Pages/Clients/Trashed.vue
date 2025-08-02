<template>
  <AppLayout>
    <div class="custom-title mb-4">Trashed Clients</div>
    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">Client ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Branch</th>
          <th class="text-left">Address</th>
          <th class="text-left">Type</th>
          <th class="text-left">Deleted At</th>
          <th class="text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="client in clients" :key="client.id">
          <td>{{ client.id }}</td>
          <td>{{ client.name }}</td>
          <td>{{ client.branch }}</td>
          <td>{{ client.address }}</td>
          <td>{{ client.type }}</td>
          <td>{{ dayjs(client.deleted_at).format('MM/DD/YYYY') }}</td>
          <td>
            <v-btn
              color="success"
              variant="text"
              size="small"
              @click="openRestoreDialog(client)"
            >
              Restore
            </v-btn>
            <v-btn
              color="error"
              variant="text"
              size="small"
              @click="openForceDeleteDialog(client)"
            >
              Delete Permanently
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>
    <Link :href="route('clients.index')">
      <v-btn class="mt-4" color="primary" variant="flat">Back to List</v-btn>
    </Link>

    <v-dialog v-model="confirmRestoreDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Restore</v-card-title>
        <v-card-text>
          Are you sure you want to restore
          <strong v-if="clientToRestore">{{ clientToRestore.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmRestoreDialog = false">Cancel</v-btn>
          <v-btn color="success" variant="flat" @click="restoreClient">Restore</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="confirmForceDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Permanent Delete</v-card-title>
        <v-card-text>
          Are you sure you want to permanently delete
          <strong v-if="clientToForceDelete">{{ clientToForceDelete.name }}</strong>?
          <br>
          <span class="text-error">This action cannot be undone.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmForceDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="forceDeleteClient">Delete Permanently</v-btn>
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
  clients: Array
})

const confirmRestoreDialog = ref(false)
const clientToRestore = ref(null)
const confirmForceDeleteDialog = ref(false)
const clientToForceDelete = ref(null)

function openRestoreDialog(client) {
  clientToRestore.value = client
  confirmRestoreDialog.value = true
}

function restoreClient() {
  if (clientToRestore.value) {
    router.post(route('clients.restore', clientToRestore.value.id))
    confirmRestoreDialog.value = false
    clientToRestore.value = null
  }
}

function openForceDeleteDialog(client) {
  clientToForceDelete.value = client
  confirmForceDeleteDialog.value = true
}

function forceDeleteClient() {
  if (clientToForceDelete.value) {
    router.delete(route('clients.forceDelete', clientToForceDelete.value.id))
    confirmForceDeleteDialog.value = false
    clientToForceDelete.value = null
  }
}
</script>