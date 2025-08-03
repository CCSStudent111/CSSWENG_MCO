<template>
  <AppLayout>
    <div class="custom-title mb-4">Department Document Type Settings</div>
   
    <!-- Department Selection -->
    <div class="mb-6">
      <v-card>
        <v-card-title>Select Department</v-card-title>
        <v-card-text>
          <v-select
            v-model="selectedDepartmentId"
            :items="departments"
            item-title="name"
            item-value="id"
            label="Choose a department to configure"
            variant="outlined"
            density="comfortable"
            clearable
          ></v-select>
        </v-card-text>
      </v-card>
    </div>

    <!-- Selected Department Info -->
    <div v-if="selectedDepartment" class="mb-4">
      <v-alert type="success">
        Selected: {{ selectedDepartment.name }}
      </v-alert>
    </div>

    <!-- Document Types Configuration -->
    <div v-if="selectedDepartment">
      <v-card>
        <v-card-title class="d-flex align-center justify-space-between">
          <div class="d-flex align-center">
            <v-icon class="mr-2">mdi-file-document-multiple</v-icon>
            Document Types for {{ selectedDepartment.name }}
          </div>
          <v-chip color="primary" variant="tonal">
            {{ assignedDocumentTypes.length }} / {{ documentTypes.length }} assigned
          </v-chip>
        </v-card-title>
        
        <v-card-text>
          <!-- Quick Actions -->
          <div class="mb-4 d-flex gap-2">
            <v-btn 
              color="success" 
              variant="outlined" 
              size="small"
              @click="assignAllTypes"
              :disabled="assignedDocumentTypes.length === documentTypes.length"
            >
              <v-icon left>mdi-check-all</v-icon>
              Assign All
            </v-btn>
            <v-btn 
              color="error" 
              variant="outlined" 
              size="small"
              @click="removeAllTypes"
              :disabled="assignedDocumentTypes.length === 0"
            >
              <v-icon left>mdi-close-circle</v-icon>
              Remove All
            </v-btn>
          </div>

          <!-- Search -->
          <v-text-field
            v-model="search"
            label="Search document types"
            prepend-inner-icon="mdi-magnify"
            clearable
            variant="outlined"
            density="compact"
            class="mb-4"
          ></v-text-field>

          <!-- Document Types Grid -->
          <v-row>
            <v-col 
              v-for="docType in filteredDocumentTypes" 
              :key="docType.id"
              cols="12" 
              sm="6" 
              md="4"
            >
              <v-card 
                :class="[
                  'document-type-card',
                  isAssigned(docType.id) ? 'assigned' : 'unassigned'
                ]"
                @click="toggleDocumentType(docType)"
                hover
              >
                <v-card-text class="pa-3">
                  <div class="d-flex align-center justify-space-between">
                    <div class="flex-grow-1">
                      <div class="font-weight-medium">{{ docType.name }}</div>
                      <div class="text-caption text-grey">{{ docType.description || 'No description' }}</div>
                    </div>
                    <v-icon 
                      :color="isAssigned(docType.id) ? 'success' : 'grey-lighten-1'"
                      size="24"
                    >
                      {{ isAssigned(docType.id) ? 'mdi-check-circle' : 'mdi-circle-outline' }}
                    </v-icon>
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>

          <!-- Empty State -->
          <div v-if="filteredDocumentTypes.length === 0" class="text-center py-8">
            <v-icon size="64" color="grey-lighten-1">mdi-file-document-off</v-icon>
            <div class="text-h6 mt-4 text-grey-darken-1">No document types found</div>
            <div class="text-body-2 text-grey-darken-1">
              Try adjusting your search terms.
            </div>
          </div>
        </v-card-text>
      </v-card>
    </div>

    <!-- Empty State for No Department Selected -->
    <div v-else class="text-center py-8">
      <v-icon size="64" color="grey-lighten-1">mdi-domain</v-icon>
      <div class="text-h6 mt-4 text-grey-darken-1">Select a Department</div>
      <div class="text-body-2 text-grey-darken-1">
        Choose a department above to configure its document type access.
      </div>
    </div>

    <!-- Loading Overlay -->
    <v-overlay v-model="loading" class="align-center justify-center">
      <v-progress-circular
        color="primary"
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>

    <!-- Success Snackbar -->
    <v-snackbar
      v-model="showSuccess"
      color="success"
      :timeout="3000"
    >
      {{ successMessage }}
      <template v-slot:actions>
        <v-btn
          color="white"
          variant="text"
          @click="showSuccess = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>

    <!-- Error Snackbar -->
    <v-snackbar
      v-model="showError"
      color="error"
      :timeout="5000"
    >
      {{ errorMessage }}
      <template v-slot:actions>
        <v-btn
          color="white"
          variant="text"
          @click="showError = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  departments: { type: Array, default: () => [] },
  documentTypes: { type: Array, default: () => [] },
})

// Reactive data
const selectedDepartmentId = ref(null)
const search = ref('')
const loading = ref(false)
const showSuccess = ref(false)
const showError = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// Computed properties - USE PROPS, NOT pageProps
const selectedDepartment = computed(() => {
  return props.departments.find(dept => dept.id === selectedDepartmentId.value)
})

const assignedDocumentTypes = computed(() => {
  if (!selectedDepartment.value) return []
  return selectedDepartment.value.document_types || []
})

const filteredDocumentTypes = computed(() => {
  if (!search.value) return props.documentTypes
  
  return props.documentTypes.filter(docType =>
    docType.name.toLowerCase().includes(search.value.toLowerCase()) ||
    (docType.description && docType.description.toLowerCase().includes(search.value.toLowerCase()))
  )
})

// Helper functions
function isAssigned(documentTypeId) {
  return assignedDocumentTypes.value.some(dt => dt.id === documentTypeId)
}

function toggleDocumentType(docType) {
  if (!selectedDepartment.value) return

  const isCurrentlyAssigned = isAssigned(docType.id)
  
  if (isCurrentlyAssigned) {
    detachDocumentType(docType)
  } else {
    attachDocumentType(docType)
  }
}

function attachDocumentType(docType) {
  loading.value = true
  
  router.post(route('department-document-types.attach', {
    department: selectedDepartment.value.id,
    type: docType.id
  }), {}, {
    onSuccess: () => {
      showSuccessMessage(`${docType.name} assigned to ${selectedDepartment.value.name}`)
    },
    onError: (errors) => {
      showErrorMessage('Failed to assign document type')
      console.error('Attach error:', errors)
    },
    onFinish: () => {
      loading.value = false
    },
    preserveScroll: true
  })
}

function detachDocumentType(docType) {
  loading.value = true
  
  router.delete(route('department-document-types.detach', {
    department: selectedDepartment.value.id,
    type: docType.id
  }), {
    onSuccess: () => {
      showSuccessMessage(`${docType.name} removed from ${selectedDepartment.value.name}`)
    },
    onError: (errors) => {
      showErrorMessage('Failed to remove document type')
      console.error('Detach error:', errors)
    },
    onFinish: () => {
      loading.value = false
    },
    preserveScroll: true
  })
}

function assignAllTypes() {
  const unassignedTypes = props.documentTypes.filter(dt => !isAssigned(dt.id))
  
  if (unassignedTypes.length === 0) return
  
  loading.value = true
  
  router.post(route('department-document-types.bulk-attach', selectedDepartment.value.id), {
    document_type_ids: unassignedTypes.map(dt => dt.id)
  }, {
    onSuccess: () => {
      showSuccessMessage('All document types assigned successfully')
    },
    onError: (errors) => {
      showErrorMessage('Failed to assign all document types')
      console.error('Bulk attach error:', errors)
    },
    onFinish: () => {
      loading.value = false
    },
    preserveScroll: true
  })
}

function removeAllTypes() {
  if (assignedDocumentTypes.value.length === 0) return
  
  loading.value = true
  
  router.post(route('department-document-types.bulk-detach', selectedDepartment.value.id), {
    document_type_ids: assignedDocumentTypes.value.map(dt => dt.id)
  }, {
    onSuccess: () => {
      showSuccessMessage('All document types removed successfully')
    },
    onError: (errors) => {
      showErrorMessage('Failed to remove all document types')
      console.error('Bulk detach error:', errors)
    },
    onFinish: () => {
      loading.value = false
    },
    preserveScroll: true
  })
}

function showSuccessMessage(message) {
  successMessage.value = message
  showSuccess.value = true
}

function showErrorMessage(message) {
  errorMessage.value = message
  showError.value = true
}

// Watch for department changes to reset search
watch(selectedDepartmentId, () => {
  search.value = ''
})
</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}

.document-type-card {
  cursor: pointer;
  transition: all 0.2s ease;
  border: 2px solid transparent;
}

.document-type-card.assigned {
  border-color: #4caf50;
  background-color: rgba(76, 175, 80, 0.05);
}

.document-type-card.unassigned {
  border-color: rgba(0, 0, 0, 0.12);
}

.document-type-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.document-type-card.assigned:hover {
  border-color: #388e3c;
  background-color: rgba(76, 175, 80, 0.1);
}

.document-type-card.unassigned:hover {
  border-color: #1976d2;
  background-color: rgba(25, 118, 210, 0.05);
}
</style>