<template>
  <v-card class="pa-6 mb-4 h-100" outlined>
    <div class="d-flex justify-between align-center mb-4">
      <div class="text-h6 mr-4">User Analytics</div>
      <v-select
        v-model="selectedTimeFrame"
        :items="timeFrameOptions"
        density="compact"
        variant="outlined"
        style="width: 130px;"
        hide-details
      ></v-select>
    </div>
    
    <v-row>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="primary">mdi-account</v-icon>
          <div class="text-h5 mt-2">{{ users.length }}</div>
          <div class="grey--text">Total Users</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="success">mdi-account-plus</v-icon>
          <div class="text-h5 mt-2">{{ newUsersInTimeFrame }}</div>
          <div class="grey--text">New {{ timeFrameLabel }}</div>
        </v-card>
      </v-col>
    </v-row>
    
    <v-row>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="info">mdi-shield-account</v-icon>
          <div class="text-h5 mt-2">{{ adminUsers }}</div>
          <div class="grey--text">Admin Users</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="warning">mdi-account-group</v-icon>
          <div class="text-h5 mt-2">{{ regularUsers }}</div>
          <div class="grey--text">Regular Users</div>
        </v-card>
      </v-col>
    </v-row>
    
    <v-row>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="purple">mdi-chart-timeline-variant</v-icon>
          <div class="text-h5 mt-2">{{ activeUsers }}</div>
          <div class="grey--text">Active {{ timeFrameLabel }}</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="orange">mdi-office-building</v-icon>
          <div class="text-h5 mt-2">{{ totalDepartments }}</div>
          <div class="grey--text">Departments</div>
        </v-card>
      </v-col>
    </v-row>

    <!-- Most Active Department -->
    <v-row>
      <v-col cols="12">
        <v-card class="pa-3 mb-2" outlined>
          <div class="d-flex align-center">
            <v-icon size="32" color="teal" class="mr-3">mdi-trophy</v-icon>
            <div>
              <div class="text-h6">{{ mostActiveDepartment?.name || 'N/A' }}</div>
              <div class="grey--text text-caption">Most Active Department</div>
              <div class="grey--text text-caption">{{ mostActiveDepartment?.userCount || 0 }} users</div>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    
    <div class="d-flex justify-end mt-2">
      <v-btn color="primary" variant="flat" href="/users">
        Manage Users
      </v-btn>
    </div>
  </v-card>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  users: { type: Array, required: true },
  documents: { type: Array, default: () => [] },
  departments: { type: Array, default: () => [] }
})

const selectedTimeFrame = ref('allTime')
const timeFrameOptions = [
  { title: 'Today', value: 'today' },
  { title: 'This Week', value: 'thisWeek' },
  { title: 'This Month', value: 'thisMonth' },
  { title: 'Last 30 Days', value: 'last30Days' },
  { title: 'This Year', value: 'thisYear' },
  { title: 'All Time', value: 'allTime' }
]

const timeFrameLabel = computed(() => {
  const option = timeFrameOptions.find(opt => opt.value === selectedTimeFrame.value)
  return option ? option.title : 'All Time'
})

// Helper function to check if a date falls within the selected timeframe
const isDateInTimeFrame = (date, timeFrame) => {
  if (!date) return false
  
  const now = new Date()
  const userDate = new Date(date)
  
  if (isNaN(userDate.getTime())) return false
  
  switch (timeFrame) {
    case 'today':
      return (
        userDate.getDate() === now.getDate() &&
        userDate.getMonth() === now.getMonth() &&
        userDate.getFullYear() === now.getFullYear()
      )
    
    case 'thisWeek':
      const startOfWeek = new Date(now)
      startOfWeek.setDate(now.getDate() - now.getDay())
      startOfWeek.setHours(0, 0, 0, 0)
      const endOfWeek = new Date(startOfWeek)
      endOfWeek.setDate(startOfWeek.getDate() + 6)
      endOfWeek.setHours(23, 59, 59, 999)
      return userDate >= startOfWeek && userDate <= endOfWeek
    
    case 'thisMonth':
      return (
        userDate.getMonth() === now.getMonth() &&
        userDate.getFullYear() === now.getFullYear()
      )
    
    case 'last30Days':
      const thirtyDaysAgo = new Date(now)
      thirtyDaysAgo.setDate(now.getDate() - 30)
      return userDate >= thirtyDaysAgo && userDate <= now
    
    case 'thisYear':
      return userDate.getFullYear() === now.getFullYear()
    
    case 'allTime':
      return true
    
    default:
      return true
  }
}

// New users in selected time frame
const newUsersInTimeFrame = computed(() => {
  if (selectedTimeFrame.value === 'allTime') {
    return props.users.length
  }
  
  return props.users.filter(user => 
    isDateInTimeFrame(user.created_at, selectedTimeFrame.value)
  ).length
})

// Admin users count
const adminUsers = computed(() => {
  return props.users.filter(user => user.is_admin === true || user.is_admin === 1).length
})

// Regular users count
const regularUsers = computed(() => {
  return props.users.filter(user => !user.is_admin || user.is_admin === false || user.is_admin === 0).length
})

// Active users (users who created documents in timeframe)
const activeUsers = computed(() => {
  if (selectedTimeFrame.value === 'allTime') {
    // Users who have created at least one document
    const activeUserIds = new Set(props.documents.map(doc => doc.created_by || doc.creator_id))
    return activeUserIds.size
  }
  
  // Users who created documents in the selected timeframe
  const documentsInTimeFrame = props.documents.filter(doc => 
    isDateInTimeFrame(doc.issued_at || doc.created_at, selectedTimeFrame.value)
  )
  const activeUserIds = new Set(documentsInTimeFrame.map(doc => doc.created_by || doc.creator_id))
  return activeUserIds.size
})

// Total departments
const totalDepartments = computed(() => {
  if (props.departments && props.departments.length > 0) {
    return props.departments.length
  }
  
  // Fallback: count unique departments from users
  const departmentIds = new Set(
    props.users
      .filter(user => user.department_id)
      .map(user => user.department_id)
  )
  return departmentIds.size
})

// Most active department
const mostActiveDepartment = computed(() => {
  // Count users per department
  const departmentCounts = {}
  
  if (props.departments && props.departments.length > 0) {
    // Initialize with department names
    props.departments.forEach(dept => {
      departmentCounts[dept.id] = {
        id: dept.id,
        name: dept.name,
        userCount: 0
      }
    })
    
    // Count users in each department
    props.users.forEach(user => {
      if (user.department_id && departmentCounts[user.department_id]) {
        departmentCounts[user.department_id].userCount++
      }
    })
    
    // Find department with most users
    const departments = Object.values(departmentCounts)
    return departments.reduce((max, dept) => 
      dept.userCount > max.userCount ? dept : max, 
      { userCount: 0, name: 'N/A' }
    )
  }
  
  return { name: 'N/A', userCount: 0 }
})
</script>

<style scoped>
.v-card .v-card {
  transition: transform 0.2s ease-in-out;
}

.v-card .v-card:hover {
  transform: translateY(-2px);
}
</style>