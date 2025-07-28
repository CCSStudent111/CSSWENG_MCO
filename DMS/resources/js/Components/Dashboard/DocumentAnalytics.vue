<template>
  <v-card class="pa-6 mb-4 h-100" outlined>
    <div class="text-h6 mb-4">Document Analytics</div>
    <v-row>
      <v-col cols="12" sm="4">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="primary">mdi-file-document</v-icon>
          <div class="text-h5 mt-2">{{ documents.length }}</div>
          <div class="grey--text">Total Documents</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="4">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="success">mdi-calendar-plus</v-icon>
          <div class="text-h5 mt-2">{{ documentsThisMonth }}</div>
          <div class="grey--text">Created This Month</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="4">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="error">mdi-delete</v-icon>
          <div class="text-h5 mt-2">{{ documentsDeletedThisMonth }}</div>
          <div class="grey--text">Deleted This Month</div>
        </v-card>
      </v-col>
    </v-row>
    <div class="d-flex justify-end mt-2">
      <Link href="/documents">
        <v-btn color="primary" variant="flat">
          Go to Documents
        </v-btn>
      </Link>
    </div>
  </v-card>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  documents: { type: Array, required: true }
})

const documentsThisMonth = computed(() => {
  const now = new Date()
  return props.documents.filter(doc => {
    const created = new Date(doc.issued_at)
    return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear()
  }).length
})

const documentsDeletedThisMonth = computed(() => {
  const now = new Date()
  return props.documents.filter(doc => {
    if (!doc.deleted_at) return false
    const deleted = new Date(doc.deleted_at)
    return deleted.getMonth() === now.getMonth() && deleted.getFullYear() === now.getFullYear()
  }).length
})
</script>