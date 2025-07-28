<template>
  <AppLayout>
    <div class="custom-title mb-4">Dashboard</div>
    
    <v-container fluid>
      <!-- Document Analytics Row -->
      <v-row align="stretch" justify="center">
        <v-col cols="12" md="5" lg="4">
          <DocumentAnalytics :documents="documents" />
        </v-col>
        <v-col cols="12" md="5" lg="4">
          <DocumentActivityChart :documents="documents" />
        </v-col>
      </v-row>
      
      <!-- User Analytics Row -->
      <v-row align="stretch" justify="center">
        <v-col cols="12" md="5" lg="4">
          <UserAnalytics :users="users" />
        </v-col>
        <v-col cols="12" md="5" lg="4">
          <UserDepartmentChart :users="users" :departments="departments" />
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DocumentAnalytics from '@/Components/Dashboard/DocumentAnalytics.vue'
import DocumentActivityChart from '@/Components/Dashboard/DocumentActivityChart.vue'
import UserAnalytics from '@/Components/Dashboard/UserAnalytics.vue'
import UserDepartmentChart from '@/Components/Dashboard/UserDepartmentChart.vue'

defineProps({
  documents: { type: Array, default: () => [] },
  clients: { type: Array, default: () => [] },
  users: { type: Array, default: () => [] },
  departments: { type: Array, default: () => [] },
  currentUser: { type: Object, default: () => ({}) }
})
</script>

<style scoped>
.custom-title {
  font-size: 28px;
  font-weight: 600;
  color: #333;
}
</style>

