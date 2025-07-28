<template>
  <v-card class="pa-6 mb-4 h-100" outlined>
    <div class="text-h6 mb-4">User Analytics</div>
    <v-row>
      <v-col cols="12">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="primary">mdi-account</v-icon>
          <div class="text-h5 mt-2">{{ users.length }}</div>
          <div class="grey--text">Total Users</div>
        </v-card>
      </v-col>
    </v-row>
  </v-card>
</template>

<script setup>
defineProps({
  users: { type: Array, required: true }
})
</script>