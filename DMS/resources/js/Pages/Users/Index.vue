<template>
  <AppLayout>
    <div class="custom-title mb-4">Manage Users</div>
    <div class="d-flex mb-4 justify-end">
      <Link :href="route('users.create')">
        <v-btn color="primary" variant="flat" size="small">
          Add A User
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
          label="Search Users"
          prepend-inner-icon="mdi-magnify"
          clearable
          density="compact"
          class="custom-search"
          style="flex: 1 1 0;"
        />
      </div>
    </div>

    <v-table density="comfortable">
      <thead>
        <tr>
          <th class="text-left">User ID</th>
          <th class="text-left">Username</th>
          <th class="text-left">Full Name</th>
          <th class="text-left">Date Added</th>
          <th class="text-left">Password</th>
          <th class="text-left">Department</th>
          <th class="text-left">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(user, index) in paginatedUsers"
          :key="user.id"
          :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''"
        >
          <td>{{ user.id }}</td>
          <td>{{ user.username }}</td>
          <td>{{ user.first_name }} {{ user.last_name }}</td>
          <td>{{ dayjs(user.created_at).format('MM/DD/YYYY') }}</td>
          <td>••••••••••</td>
          <td>{{ user.department.name }}</td>
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

    <div class="d-flex justify-end mt-4">
      <v-pagination
        v-model="currentPage"
        :length="totalPages"
        total-visible="7"
        size="small"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'

const props = defineProps({ users: Array })

const search = ref('')
const entries = ref(10)
const currentPage = ref(1)
const entriesOptions = [5, 10, 20, 50, 100]

const filteredUsers = computed(() => {
  if (!search.value) return props.users
  return props.users.filter((u) =>
    [u.username, u.first_name, u.last_name, u.email]
      .join(' ')
      .toLowerCase()
      .includes(search.value.toLowerCase())
  )
})

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * entries.value
  return filteredUsers.value.slice(start, start + entries.value)
})

const totalPages = computed(() => {
  return Math.ceil(filteredUsers.value.length / entries.value)
})
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
