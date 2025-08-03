<!-- LAST UPDATED BY: AVE -->

<template>
  <AppLayout>
    <div class="custom-title mb-4">Manage Clients</div>
    <div class="d-flex mb-4 justify-end">
      <template v-if="authUser.role !== 'Employee'">
        <Link :href="route('clients.create')">
         <v-btn color="primary" variant="flat" size="small">
           Add A Client
         </v-btn>
         </Link>      
      </template>
    </div>

    <div class="controls-row mb-4">
      <div class="d-flex" style="gap: 16px;">
        <v-select
          v-model="entries"
          :items="entriesOptions"
          label="Show entries"
          class="custom-entries"
          density="compact"
          hide-details
          variant="outlined"
          style="width: 160px; flex-shrink: 0;"
        ></v-select>
        <v-text-field
          v-model="search"
          label="Search Clients"
          prepend-inner-icon="mdi-magnify"
          clearable
          density="compact"
          class="custom-search"
          style="flex: 1 1 0;"
        ></v-text-field>
      </div>
    </div>

    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">Client ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Branch</th>
          <th class="text-left">Address</th>
          <th class="text-left">Type</th>
          <th class="text-left">Date Added</th>
          <th class="text-left">Last Updated</th>
          <th class="text-left">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(client, index) in paginatedClients" :key="client.id"
          :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
          <td>{{ client.id }}</td>
          <td>{{ client.name }}</td>
          <td>{{ client.branch }}</td>
          <td>{{ client.address }}</td>
          <td>{{ client.type }}</td>
          <td>{{ dayjs(client.created_at).format('MM/DD/YYYY') }}</td>
          <td>{{ dayjs(client.updated_at).format('MM/DD/YYYY') }}</td>
          <td>
            <Link :href="route('clients.show', client.id)">
            <v-btn icon size="small" variant="text" aria-label="View">
              <v-icon>mdi-eye</v-icon>
            </v-btn>
            </Link>
            <template v-if="authUser.role !== 'Employee'">
              <Link :href ="route('clients.edit', client.id)">
              <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              </Link>
  
              
              <v-btn icon size="small" color="error" variant="text" aria-label="Delete"
              @click="openDeleteDialog(client)">
              <v-icon>mdi-delete</v-icon>
              </v-btn>
            </template>
          </td>
        </tr>
      </tbody>
    </v-table>
    <div class="d-flex justify-end align-center mt-4">
      <v-pagination
        v-model="page"
        :length="pageCount"
        total-visible="7"
        color="primary"
      ></v-pagination>
      <span class="ml-4">Page {{ page }} of {{ pageCount }}</span>
    </div>

    <!-- Delete Confirmation Dialog -->
    <v-dialog v-model="confirmDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Delete</v-card-title>
        <v-card-text>
          Are you sure you want to delete
          <strong v-if="clientToDelete">{{ clientToDelete.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="confirmDelete">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <Link :href="route('clients.trashed')">
      <v-btn color="secondary" variant="outlined" size="small" class="ml-2">
        View Trashed Clients
      </v-btn>
    </Link>

  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { router, usePage } from '@inertiajs/vue3'

const authUser = usePage().props.auth.user

const props = defineProps({
  clients: Array
})

const entries = ref(10)
const entriesOptions = [5, 10, 25, 50, 100]
const search = ref('')
const page = ref(1)
const confirmDeleteDialog = ref(false)
const clientToDelete = ref(null)

function openDeleteDialog(client) {
  clientToDelete.value = client
  confirmDeleteDialog.value = true
}

function confirmDelete() {
  if (clientToDelete.value) {
    router.delete(route('clients.destroy', clientToDelete.value.id))
    confirmDeleteDialog.value = false
    clientToDelete.value = null
  }
}

// Filter clients by search
const filteredClients = computed(() =>
  props.clients.filter(client =>
    client.name.toLowerCase().includes(search.value.toLowerCase()) ||
    client.branch.toLowerCase().includes(search.value.toLowerCase()) ||
    String(client.id).includes(search.value)
  )
)

// Pagination logic
const pageCount = computed(() =>
  Math.ceil(filteredClients.value.length / entries.value)
)

const paginatedClients = computed(() => {
  const start = (page.value - 1) * entries.value
  return filteredClients.value.slice(start, start + entries.value)
})

// Reset to page 1 when entries per page changes
watch(entries, () => { page.value = 1 })
</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}
.controls-row {
  width: 100%;
  max-width: 100%;
}
.custom-table {
  width: 100%;
}
.custom-entries {
  min-width: 140px;
  max-width: 180px;
  height: 40px; 
  align-items: center;
}
.custom-search {
  width: 100%;
  height: 40px; 
}
</style>
