<template>
  <v-card class="pa-6 mb-4 h-100" outlined>
    <div class="d-flex justify-between align-center mb-4">
      <div class="text-h6 mr-4">Recent Document Activity</div>
      <v-select
        v-model="activityFilter"
        :items="filterOptions"
        density="compact"
        variant="outlined"
        style="width: 120px;"
        hide-details
      ></v-select>
    </div>
    
    <div class="activity-container" style="max-height: 400px; overflow-y: auto;">
      <v-list v-if="filteredActivities.length" density="compact">
        <v-list-item
          v-for="activity in filteredActivities"
          :key="activity.id"
          class="mb-2"
          rounded
        >
          <template #prepend>
            <v-avatar size="32" :color="getActivityColor(activity.type)">
              <v-icon size="16" color="white">{{ getActivityIcon(activity.type) }}</v-icon>
            </v-avatar>
          </template>
          
          <v-list-item-title class="text-body-2">
            <strong>{{ activity.document_name }}</strong>
          </v-list-item-title>
          
          <v-list-item-subtitle class="text-caption">
            {{ getActivityDescription(activity) }}
            <div class="text-caption text-grey mt-1">
              {{ formatTimeAgo(activity.created_at) }}
            </div>
          </v-list-item-subtitle>

          <template #append>
            <v-btn
              :href="`/documents/${activity.document_id}`"
              icon="mdi-eye"
              variant="text"
              size="small"
              @click.stop
            ></v-btn>
          </template>
        </v-list-item>
      </v-list>
      
      <div v-else class="text-center pa-4">
        <v-icon size="48" color="grey">mdi-clipboard-text-outline</v-icon>
        <div class="text-body-2 text-grey mt-2">No recent activity</div>
      </div>
    </div>
  </v-card>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  documents: { type: Array, required: true }
})

const activityFilter = ref('all')
const filterOptions = [
  { title: 'All', value: 'all' },
  { title: 'Created', value: 'created' },
  { title: 'Approved', value: 'approved' },
  { title: 'Pending', value: 'pending' }
]

// Generate activity data from documents
const documentActivities = computed(() => {
  const activities = []
  
  props.documents.forEach(doc => {
    // Document creation activity
    activities.push({
      id: `create-${doc.id}`,
      document_id: doc.id,
      document_name: doc.name,
      type: 'created',
      status: doc.status,
      creator: doc.creator?.username || 'Unknown',
      created_at: doc.issued_at || doc.created_at
    })
    
    // Document approval activity (if approved)
    if (doc.status === 'approved' && doc.approved_at) {
      activities.push({
        id: `approve-${doc.id}`,
        document_id: doc.id,
        document_name: doc.name,
        type: 'approved',
        status: doc.status,
        approver: doc.approver?.username || 'Unknown',
        created_at: doc.approved_at
      })
    }
    
    // Document pending activity (if pending)
    if (doc.status === 'pending') {
      activities.push({
        id: `pending-${doc.id}`,
        document_id: doc.id,
        document_name: doc.name,
        type: 'pending',
        status: doc.status,
        creator: doc.creator?.username || 'Unknown',
        created_at: doc.issued_at || doc.created_at
      })
    }
  })
  
  // Sort by most recent first
  return activities.sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 10)
})

const filteredActivities = computed(() => {
  if (activityFilter.value === 'all') {
    return documentActivities.value
  }
  
  return documentActivities.value.filter(activity => activity.type === activityFilter.value)
})

const getActivityColor = (type) => {
  switch (type) {
    case 'created': return 'success'
    case 'approved': return 'info'
    case 'pending': return 'warning'
    case 'rejected': return 'error'
    default: return 'grey'
  }
}

const getActivityIcon = (type) => {
  switch (type) {
    case 'created': return 'mdi-plus'
    case 'approved': return 'mdi-check'
    case 'pending': return 'mdi-clock'
    case 'rejected': return 'mdi-close'
    default: return 'mdi-file-document'
  }
}

const getActivityDescription = (activity) => {
  switch (activity.type) {
    case 'created':
      return `Created by ${activity.creator}`
    case 'approved':
      return `Approved by ${activity.approver}`
    case 'pending':
      return `Pending approval from ${activity.creator}`
    case 'rejected':
      return `Rejected by ${activity.rejector}`
    default:
      return 'Document activity'
  }
}

const formatTimeAgo = (date) => {
  const now = new Date()
  const activityDate = new Date(date)
  const diffInSeconds = Math.floor((now - activityDate) / 1000)
  
  if (diffInSeconds < 60) {
    return 'Just now'
  } else if (diffInSeconds < 3600) {
    const minutes = Math.floor(diffInSeconds / 60)
    return `${minutes} minute${minutes > 1 ? 's' : ''} ago`
  } else if (diffInSeconds < 86400) {
    const hours = Math.floor(diffInSeconds / 3600)
    return `${hours} hour${hours > 1 ? 's' : ''} ago`
  } else if (diffInSeconds < 604800) {
    const days = Math.floor(diffInSeconds / 86400)
    return `${days} day${days > 1 ? 's' : ''} ago`
  } else {
    return activityDate.toLocaleDateString()
  }
}
</script>

<style scoped>
.activity-container {
  border-radius: 4px;
}

.v-list-item {
  border: 1px solid #f0f0f0;
  margin-bottom: 8px;
}

.v-list-item:hover {
  background-color: #f8f9fa;
}
</style>