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
        <tr v-for="documentType in filteredDocTypes" :key="documentType.id">
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
import { ref, computed } from 'vue'

const props = defineProps({
  documentTypes: Array
})

const filter = ref('all')

const filteredDocTypes = computed(() => {
  if (filter.value === 'active') {
    return props.documentTypes.filter(d => !d.deleted_at)
  }
  if (filter.value === 'trashed') {
    return props.documentTypes.filter(d => d.deleted_at)
  }
  return props.documentTypes
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
    router.delete(route('documentTypes.destroy', documentTypeToDelete.value.id))
    confirmDeleteDialog.value = false
    documentTypeToDelete.value = null
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