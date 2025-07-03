<!-- LAST UPDATED BY: AVE -->

<template>
  <AppLayout>
    <div class="custom-title mb-4">Manage Hospitals</div>
    <div class="d-flex mb-4 justify-end">
      <Link :href="route('hospital-documents.create')">
      <v-btn color="primary" variant="flat" size="small">
        Add A Hospital
      </v-btn>
      </Link>      
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
          label="Search Hospitals"
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
          <th class="text-left">Hospital ID</th>
          <th class="text-left">Name</th>
          <th class="text-left">Branch</th>
          <th class="text-left">Date Added</th>
          <th class="text-left">Last Updated</th>
          <th class="text-left">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(hospital, index) in paginatedHospitals" :key="hospital.id"
          :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
          <td>{{ hospital.id }}</td>
          <td>{{ hospital.name }}</td>
          <td>{{ hospital.branch }}</td>
          <td>{{ dayjs(hospital.created_at).format('MM/DD/YYYY') }}</td>
          <td>{{ dayjs(hospital.updated_at).format('MM/DD/YYYY') }}</td>
          <td>
            <Link :href="route('hospitals.show', hospital.id)">
            <v-btn icon size="small" variant="text" aria-label="View">
              <v-icon>mdi-eye</v-icon>
            </v-btn>
            </Link>

            <Link :href ="route('hospitals.edit', hospital.id)">
            <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            </Link>

            
            <v-btn icon size="small" color="error" variant="text" aria-label="Delete" 
            @click="deleteHospital(hospital.id)"><v-icon>mdi-delete</v-icon>
            </v-btn>
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
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  hospitals: Array
})

const entries = ref(10)
const entriesOptions = [5, 10, 25, 50, 100]
const search = ref('')
const page = ref(1)

function deleteHospital(id){
  if (confirm('Are you sure you want to delete this hospital?')) {
    router.delete(route('hospitals.destroy', id))
  }
}

// Filter hospitals by search
const filteredHospitals = computed(() =>
  props.hospitals.filter(hospital =>
    hospital.name.toLowerCase().includes(search.value.toLowerCase()) ||
    hospital.branch.toLowerCase().includes(search.value.toLowerCase()) ||
    String(hospital.id).includes(search.value)
  )
)

// Pagination logic
const pageCount = computed(() =>
  Math.ceil(filteredHospitals.value.length / entries.value)
)

const paginatedHospitals = computed(() => {
  const start = (page.value - 1) * entries.value
  return filteredHospitals.value.slice(start, start + entries.value)
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
