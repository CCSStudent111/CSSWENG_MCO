<template>
  <AppLayout>
    <div class="custom-title mb-4">Document Types</div>
    <div class="mb-4 d-flex justify-space-between align-center">
      <v-btn-toggle v-model="filter" mandatory>
        <v-btn value="all" color="primary" variant="outlined">All</v-btn>
        <v-btn value="active" color="primary" variant="outlined">Active</v-btn>
        <v-btn value="trashed" color="primary" variant="outlined">Trashed</v-btn>
      </v-btn-toggle>
      <Link :href="route('documentTypes.create')">
        <v-btn color="primary" variant="flat" class="ml-4">
          <v-icon left>mdi-plus</v-icon>
          Add Document Type
        </v-btn>
      </Link>
    </div>

    <!-- Search and Pagination Controls -->
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
          label="Search Document Types"
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
          <th class="text-left">ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Status</th>
          <th class="text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(documentType, index) in paginatedDocTypes" :key="documentType.id"
          :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
          <td>{{ documentType.id }}</td>
          <td>{{ documentType.name }}</td>
          <td>
            <v-chip :color="documentType.deleted_at ? 'error' : 'success'" size="small" variant="tonal">
              {{ documentType.deleted_at ? 'Trashed' : 'Active' }}
            </v-chip>
          </td>
          <td>
            <Link :href="route('documentTypes.edit', documentType.id)">
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
              @click="openDeleteDialog(documentType)"
              v-if="!documentType.deleted_at"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
            <v-btn
              icon
              size="small"
              color="success"
              variant="text"
              aria-label="Restore"
              @click="openRestoreDialog(documentType)"
              v-if="documentType.deleted_at"
            >
              <v-icon>mdi-restore</v-icon>
            </v-btn>
            <v-btn
              icon
              size="small"
              color="error"
              variant="text"
              aria-label="Force Delete"
              @click="openForceDeleteDialog(documentType)"
              v-if="documentType.deleted_at"
            >
              <v-icon>mdi-delete-forever</v-icon>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <!-- Pagination -->
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
          <strong v-if="documentTypeToDelete">{{ documentTypeToDelete.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="deleteDocType">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Restore Confirmation Dialog -->
    <v-dialog v-model="confirmRestoreDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Restore</v-card-title>
        <v-card-text>
          Are you sure you want to restore
          <strong v-if="documentTypeToRestore">{{  documentTypeToRestore.name }}</strong>?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmRestoreDialog = false">Cancel</v-btn>
          <v-btn color="success" variant="flat" @click="restoreDocType">Restore</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Force Delete Confirmation Dialog -->
    <v-dialog v-model="confirmForceDeleteDialog" max-width="400">
      <v-card>
        <v-card-title>Confirm Permanent Delete</v-card-title>
        <v-card-text>
          Are you sure you want to permanently delete
          <strong v-if="documentTypeToForceDelete">{{ documentTypeToForceDelete.name }}</strong>?
          <br>
          <span class="text-error">This action cannot be undone.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" variant="text" @click="confirmForceDeleteDialog = false">Cancel</v-btn>
          <v-btn color="error" variant="flat" @click="forceDeleteDocType">Delete Permanently</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
const { props: pageProps } = usePage()

const props = defineProps({
  documentTypes: Array
})

const filter = ref('all')
const entries = ref(10)
const entriesOptions = [5, 10, 25, 50, 100]
const search = ref('')
const page = ref(1)

// Filter and search logic
const filteredDocTypes = computed(() => {
  let filtered = props.documentTypes

  // Filter by status
  if (filter.value === 'active') {
    filtered = filtered.filter(d => !d.deleted_at)
  } else if (filter.value === 'trashed') {
    filtered = filtered.filter(d => d.deleted_at)
  }

  // Filter by search
  if (search.value) {
    filtered = filtered.filter(docType =>
      docType.name.toLowerCase().includes(search.value.toLowerCase()) ||
      String(docType.id).includes(search.value)
    )
  }

  return filtered
})

// Pagination logic
const pageCount = computed(() => {
  return Math.ceil(filteredDocTypes.value.length / entries.value) || 1
})

const paginatedDocTypes = computed(() => {
  const start = (page.value - 1) * entries.value
  return filteredDocTypes.value.slice(start, start + entries.value)
})

// Reset to page 1 when entries per page changes or search changes
watch([entries, search, filter], () => { 
  page.value = 1 
})

const confirmDeleteDialog = ref(false)
const documentTypeToDelete = ref(null)
const confirmRestoreDialog = ref(false)
const documentTypeToRestore = ref(null)
const confirmForceDeleteDialog = ref(false)
const documentTypeToForceDelete = ref(null)

function openDeleteDialog(documentType) {
  documentTypeToDelete.value = documentType
  confirmDeleteDialog.value = true
}

function deleteDocType() {
  if (documentTypeToDelete.value) {
    console.log('Deleting document type:', documentTypeToDelete.value)
    console.log('Route URL:', route('documentTypes.destroy', documentTypeToDelete.value.id))
    
    router.delete(route('documentTypes.destroy', documentTypeToDelete.value.id), {
      onStart: () => {
        console.log('Delete request started')
      },
      onSuccess: (page) => {
        console.log('Delete successful:', page)
        confirmDeleteDialog.value = false
        documentTypeToDelete.value = null
      },
      onError: (errors) => {
        console.error('Delete failed:', errors)
        // Keep dialog open to show errors
      },
      onFinish: () => {
        console.log('Delete request finished')
      }
    })
  }
}

function openRestoreDialog(documentType) {
  documentTypeToRestore.value = documentType
  confirmRestoreDialog.value = true
}

function restoreDocType() {
  if (documentTypeToRestore.value) {
    router.post(route('documentTypes.restore', documentTypeToRestore.value.id))
    confirmRestoreDialog.value = false
    documentTypeToRestore.value = null
  }
}

function openForceDeleteDialog(documentType) {
  documentTypeToForceDelete.value = documentType
  confirmForceDeleteDialog.value = true
}

function forceDeleteDocType() {
  if (documentTypeToForceDelete.value) {
    router.delete(route('documentTypes.forceDelete', documentTypeToForceDelete.value.id))
    confirmForceDeleteDialog.value = false
    documentTypeToForceDelete.value = null
  }
}
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