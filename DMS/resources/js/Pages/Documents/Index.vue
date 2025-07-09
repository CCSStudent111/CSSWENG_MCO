<template>
  <AppLayout>
    <div class="custom-title mb-4">Manage Documents</div>

    <!-- Search Filters -->
    <div class="d-flex align-center gap-4 mb-4">
      <v-text-field
        v-model="searchQuery"
        label="Search"
        placeholder="Enter search keyword"
        @keyup.enter="fetchDocuments"
        density="compact"
        clearable
      />

      <v-select
        v-model="searchType"
        :items="searchTypes"
        label="Search By"
        density="compact"
        style="max-width: 200px"
      />

      <v-select
        v-if="searchType === 'tag' || searchType === 'tag+type'"
        v-model="matchType"
        :items="['all', 'any']"
        label="Match Type"
        density="compact"
        style="max-width: 150px"
      />

      <v-btn color="primary" @click="fetchDocuments">Search</v-btn>
    </div>

    <!-- Document Table -->
    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">Document ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Type</th>
          <th class="text-left">Tags</th>
          <th class="text-left">Issued At</th>
          <th class="text-left">Created By</th>
          <th class="text-left">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(document, index) in documents" :key="document.id" :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
          <td>{{ document.id }}</td>
          <td>{{ document.name }}</td>
          <td>{{ document.type.name }}</td>
          <td>
            <v-chip v-for="tag in document.tags" :key="tag.id" color="blue-lighten-3" size="small" label>
              {{ tag.name }}
            </v-chip>
          </td>
          <td>{{ dayjs(document.issued_at).format('MM/DD/YYYY') }}</td>
          <td>{{ document.creator.username }}</td>
          <td>
            <Link :href="route('documents.show', document.id)">
              <v-btn icon size="small" variant="text" aria-label="View">
                <v-icon>mdi-eye</v-icon>
              </v-btn>
            </Link>
            <Link :href="route('documents.edit', document.id)">
              <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
            </Link>
            <v-btn icon size="small" color="error" variant="text" aria-label="Delete" @click="deleteDocument(document.id)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <!-- Pagination Controls -->
    <div class="d-flex justify-between align-center mt-4">
      <v-select
        v-model="pagination.per_page"
        :items="[5, 10, 15, 20]"
        label="Items per page"
        @change="fetchDocuments"
        density="compact"
        style="max-width: 150px"
      />

      <v-pagination
        v-model="pagination.current_page"
        :length="pagination.last_page"
        @update:modelValue="fetchDocuments"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import dayjs from 'dayjs'

const documents = ref([])
const searchQuery = ref('')
const searchType = ref('document_name')
const matchType = ref('all')
const searchTypes = ['document_name', 'tag', 'document_type', 'hospital', 'tag+type']

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
})

const fetchDocuments = async () => {
  try {
    const params = {
      query: searchQuery.value,
      type: searchType.value,
      match_type: matchType.value,
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
    }

    const { data } = await axios.get(route('documents.search'), { params })
    documents.value = data.data
    pagination.value.current_page = data.current_page
    pagination.value.last_page = data.last_page
    pagination.value.per_page = data.per_page
    pagination.value.total = data.total
  } catch (err) {
    console.error('Failed to fetch documents', err)
  }
}

const deleteDocument = (id) => {
  if (confirm('Are you sure you want to delete this document?')) {
    router.delete(route('documents.destroy', id), {
      onSuccess: () => fetchDocuments(),
    })
  }
}

onMounted(() => fetchDocuments())
</script>
