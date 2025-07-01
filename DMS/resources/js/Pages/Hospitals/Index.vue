<!-- LAST UPDATED BY: FRANZ -->

<template>
  <AppLayout>
    <div class="custom-title mb-4">Manage Hospitals</div>
    <div class="d-flex mb-4 justify-end">
            <Link :href="route('hospital-documents.create')">
                <v-btn color="primary" variant="flat" size="small">
                    <v-icon start>mdi-upload</v-icon>Upload Document
                </v-btn>
            </Link>
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
        <tr v-for="(hospital, index) in hospitals" :key="hospital.id"
          :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
          <td>{{ hospital.id }}</td>
          <td>{{ hospital.name }}</td>
          <td>{{ hospital.branch }}</td>
          <td>{{ dayjs(hospital.created_at).format('MM/DD/YYYY') }}</td>
          <td>{{ dayjs(hospital.updated_at).format('MM/DD/YYYY') }}</td>
          <td>
            <v-btn icon size="small" variant="text" aria-label="View">
              <v-icon>mdi-eye</v-icon>
            </v-btn>

            <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>

            <v-btn icon size="small" color="error" variant="text" aria-label="Delete">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'
const props = defineProps({
  hospitals: Array
})

</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}
</style>
