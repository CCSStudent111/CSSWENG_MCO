<template>
  <AppLayout>
    <div class="custom-title mb-4">User Details</div>

    <v-card class="pa-4 mb-6">
      <v-row>
        <v-col cols="12" md="6">
          <strong>Username:</strong> {{ user.username }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Email:</strong> {{ user.email }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Full Name:</strong>
          {{ user.first_name }} {{ user.middle_name }} {{ user.last_name }} {{ user.suffix }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Department:</strong> {{ user.department?.name || '—' }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Role:</strong> {{ user.role }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Date of Birth:</strong>
          {{ user.date_of_birth ? dayjs(user.date_of_birth).format('MM/DD/YYYY') : '—' }}
        </v-col>
        <v-col cols="12" md="6">
          <strong>Date Created:</strong>
          {{ dayjs(user.created_at).format('MM/DD/YYYY') }}
        </v-col>
      </v-row>
    </v-card>

    <div class="d-flex justify-end">
      <Link :href="route('users.index')">
        <v-btn color="secondary" variant="outlined" size="small">
          Back to List
        </v-btn>
      </Link>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import dayjs from 'dayjs'

const props = defineProps({
  user: Object
})
</script>

<style scoped>
.custom-title {
  font-size: 2.5rem;
  font-weight: bold;
}
</style>
