<template>
  <v-card class="pa-6 mb-4 h-100" outlined>
    <div class="d-flex justify-between align-center mb-4">
      <div class="text-h6 mr-4">Document Analytics</div>
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
      <v-col cols="12" sm="4">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="primary">mdi-file-document</v-icon>
          <div class="text-h5 mt-2">{{ totalDocumentsInTimeFrame }}</div>
          <div class="grey--text">Total {{ timeFrameLabel }}</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="4">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="success">mdi-calendar-plus</v-icon>
          <div class="text-h5 mt-2">{{ documentsInTimeFrame }}</div>
          <div class="grey--text">Created {{ timeFrameLabel }}</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="4">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="warning">mdi-clock-outline</v-icon>
          <div class="text-h5 mt-2">{{ pendingDocuments }}</div>
          <div class="grey--text">Pending Approval</div>
        </v-card>
      </v-col>
    </v-row>
    
    <!-- Additional Analytics Row -->
    <v-row class="mt-2">
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="info">mdi-chart-line</v-icon>
          <div class="text-h5 mt-2">{{ approvedDocumentsInTimeFrame }}</div>
          <div class="grey--text">Approved {{ timeFrameLabel }}</div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6">
        <v-card class="pa-3 mb-2" outlined>
          <v-icon size="32" color="error">mdi-delete</v-icon>
          <div class="text-h5 mt-2">{{ documentsDeletedInTimeFrame }}</div>
          <div class="grey--text">Deleted {{ timeFrameLabel }}</div>
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
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  documents: { type: Array, required: true }
})

const selectedTimeFrame = ref('allTime') // Changed from 'thisMonth' to 'allTime'
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
  const docDate = new Date(date)
  
  // Check if date is valid
  if (isNaN(docDate.getTime())) return false
  
  switch (timeFrame) {
    case 'today':
      return (
        docDate.getDate() === now.getDate() &&
        docDate.getMonth() === now.getMonth() &&
        docDate.getFullYear() === now.getFullYear()
      )
    
    case 'thisWeek':
      const startOfWeek = new Date(now)
      startOfWeek.setDate(now.getDate() - now.getDay())
      startOfWeek.setHours(0, 0, 0, 0)
      const endOfWeek = new Date(startOfWeek)
      endOfWeek.setDate(startOfWeek.getDate() + 6)
      endOfWeek.setHours(23, 59, 59, 999)
      return docDate >= startOfWeek && docDate <= endOfWeek
    
    case 'thisMonth':
      return (
        docDate.getMonth() === now.getMonth() &&
        docDate.getFullYear() === now.getFullYear()
      )
    
    case 'last30Days':
      const thirtyDaysAgo = new Date(now)
      thirtyDaysAgo.setDate(now.getDate() - 30)
      return docDate >= thirtyDaysAgo && docDate <= now
    
    case 'thisYear':
      return docDate.getFullYear() === now.getFullYear()
    
    case 'allTime':
      return true
    
    default:
      return (
        docDate.getMonth() === now.getMonth() &&
        docDate.getFullYear() === now.getFullYear()
      )
  }
}

// Total documents in selected time frame (based on issued_at)
const totalDocumentsInTimeFrame = computed(() => {
  if (selectedTimeFrame.value === 'allTime') {
    return props.documents.length
  }
  
  return props.documents.filter(doc => 
    isDateInTimeFrame(doc.issued_at, selectedTimeFrame.value)
  ).length
})

// Documents created in selected time frame (same as total now)
const documentsInTimeFrame = computed(() => {
  return totalDocumentsInTimeFrame.value
})

// Documents deleted in selected time frame
const documentsDeletedInTimeFrame = computed(() => {
  return props.documents.filter(doc => 
    doc.deleted_at && isDateInTimeFrame(doc.deleted_at, selectedTimeFrame.value)
  ).length
})

// Pending documents within time frame
const pendingDocuments = computed(() => {
  if (selectedTimeFrame.value === 'allTime') {
    return props.documents.filter(doc => doc.status === 'pending').length
  }
  
  return props.documents.filter(doc => 
    doc.status === 'pending' && 
    isDateInTimeFrame(doc.issued_at, selectedTimeFrame.value)
  ).length
})

// Approved documents within time frame
const approvedDocumentsInTimeFrame = computed(() => {
  if (selectedTimeFrame.value === 'allTime') {
    return props.documents.filter(doc => doc.status === 'approved').length
  }
  
  return props.documents.filter(doc => 
    doc.status === 'approved' && 
    isDateInTimeFrame(doc.issued_at, selectedTimeFrame.value)
  ).length
})
</script>