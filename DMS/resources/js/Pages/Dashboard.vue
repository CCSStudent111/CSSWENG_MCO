<template>
  <AppLayout>
    <div class="custom-title mb-4">Dashboard</div>
    
    <v-container fluid>
      <!-- Document Analytics and Activity Side by Side, slimmer width -->
      <v-row align="stretch" justify="center">
        <v-col cols="12" md="5" lg="4">
          <v-card class="pa-6 mb-4 h-100" outlined>
            <div class="text-h6 mb-4">Document Analytics</div>
            <v-row>
              <v-col cols="12" sm="4">
                <v-card class="pa-3 mb-2" outlined>
                  <v-icon size="32" color="primary">mdi-file-document</v-icon>
                  <div class="text-h5 mt-2">{{ documents.length }}</div>
                  <div class="grey--text">Total Documents</div>
                </v-card>
              </v-col>
              <v-col cols="12" sm="4">
                <v-card class="pa-3 mb-2" outlined>
                  <v-icon size="32" color="success">mdi-calendar-plus</v-icon>
                  <div class="text-h5 mt-2">{{ documentsThisMonth }}</div>
                  <div class="grey--text">Created This Month</div>
                </v-card>
              </v-col>
              <v-col cols="12" sm="4">
                <v-card class="pa-3 mb-2" outlined>
                  <v-icon size="32" color="error">mdi-delete</v-icon>
                  <div class="text-h5 mt-2">{{ documentsDeletedThisMonth }}</div>
                  <div class="grey--text">Deleted This Month</div>
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
        </v-col>
        <v-col cols="12" md="5" lg="4">
          <v-card class="pa-6 mb-4 h-100" outlined>
            <div class="d-flex justify-between align-center mb-4">
              <div class="text-h6 mr-4">Document Activity</div>
              <v-select
                v-model="activityPeriod"
                :items="periodOptions"
                density="compact"
                variant="outlined"
                style="width: 140px;"
                hide-details
              ></v-select>
            </div>
            <canvas ref="docActivityChart" height="180"></canvas>
          </v-card>
        </v-col>
      </v-row>
      <!-- Users Analytics and Users per Department Side by Side, slimmer width -->
      <v-row align="stretch" justify="center">
        <v-col cols="12" md="5" lg="4">
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
        </v-col>
        <v-col cols="12" md="5" lg="4">
          <v-card class="pa-6 mb-4 h-100" outlined>
            <div class="text-h6 mb-2">Users per Department</div>
            <canvas ref="deptChart" height="180"></canvas>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  documents: { type: Array, default: () => [] },
  clients: { type: Array, default: () => [] },
  users: { type: Array, default: () => [] },
  departments: { type: Array, default: () => [] },
  currentUser: { type: Object, default: () => ({}) }
})

// Activity period selector
const activityPeriod = ref('30days')
const periodOptions = [
  { title: 'Last 7 Days', value: '7days' },
  { title: 'Last 30 Days', value: '30days' },
  { title: 'Last Year', value: '1year' }
]

// Document analytics
const documentsThisMonth = computed(() => {
  const now = new Date()
  return props.documents.filter(doc => {
    const created = new Date(doc.issued_at)
    return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear()
  }).length
})

const documentsDeletedThisMonth = computed(() => {
  const now = new Date()
  return props.documents.filter(doc => {
    if (!doc.deleted_at) return false
    const deleted = new Date(doc.deleted_at)
    return deleted.getMonth() === now.getMonth() && deleted.getFullYear() === now.getFullYear()
  }).length
})

// --- Users per Department Pie Chart ---
const deptChart = ref(null)
let deptChartInstance = null

const usersPerDepartment = computed(() => {
  const counts = {}
  props.departments.forEach(dep => {
    counts[dep.name] = 0
  })
  props.users.forEach(user => {
    const dep = props.departments.find(d => d.id === user.department_id)
    if (dep) counts[dep.name]++
  })
  return counts
})

function renderDeptChart() {
  if (!deptChart.value) return
  if (deptChartInstance) {
    deptChartInstance.destroy()
  }
  import('chart.js/auto').then(({ default: Chart }) => {
    const ctx = deptChart.value.getContext('2d')
    deptChartInstance = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: Object.keys(usersPerDepartment.value),
        datasets: [{
          label: 'Users',
          data: Object.values(usersPerDepartment.value),
          backgroundColor: [
            '#1976d2', '#43a047', '#e53935', '#fbc02d', '#8e24aa', '#00acc1', '#ff7043', '#d4e157'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true, position: 'bottom' }
        }
      }
    })
  })
}

// --- Document Activity Chart with Dynamic Period ---
const docActivityChart = ref(null)
let docActivityChartInstance = null

const docActivityData = computed(() => {
  const days = []
  const counts = []
  const now = new Date()
  
  let totalDays, dateFormat
  
  switch (activityPeriod.value) {
    case '7days':
      totalDays = 7
      dateFormat = (d) => `${d.getMonth() + 1}/${d.getDate()}`
      break
    case '30days':
      totalDays = 30
      dateFormat = (d) => `${d.getMonth() + 1}/${d.getDate()}`
      break
    case '1year':
      totalDays = 365
      dateFormat = (d) => `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear().toString().slice(-2)}`
      break
    default:
      totalDays = 30
      dateFormat = (d) => `${d.getMonth() + 1}/${d.getDate()}`
  }
  
  // For year view, group by weeks instead of days for better readability
  if (activityPeriod.value === '1year') {
    const weeks = 52
    for (let i = weeks - 1; i >= 0; i--) {
      const weekStart = new Date(now)
      weekStart.setDate(now.getDate() - (i * 7))
      const weekEnd = new Date(weekStart)
      weekEnd.setDate(weekStart.getDate() + 6)
      
      const label = `${weekStart.getMonth() + 1}/${weekStart.getDate()}`
      days.push(label)
      
      const count = props.documents.filter(doc => {
        const created = new Date(doc.issued_at)
        return created >= weekStart && created <= weekEnd
      }).length
      counts.push(count)
    }
  } else {
    // Daily view for 7 days and 30 days
    for (let i = totalDays - 1; i >= 0; i--) {
      const d = new Date(now)
      d.setDate(now.getDate() - i)
      const label = dateFormat(d)
      days.push(label)
      
      const count = props.documents.filter(doc => {
        const created = new Date(doc.issued_at)
        return (
          created.getFullYear() === d.getFullYear() &&
          created.getMonth() === d.getMonth() &&
          created.getDate() === d.getDate()
        )
      }).length
      counts.push(count)
    }
  }
  
  return { days, counts }
})

function renderDocActivityChart() {
  if (!docActivityChart.value) return
  if (docActivityChartInstance) {
    docActivityChartInstance.destroy()
  }
  import('chart.js/auto').then(({ default: Chart }) => {
    const ctx = docActivityChart.value.getContext('2d')
    docActivityChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: docActivityData.value.days,
        datasets: [{
          label: activityPeriod.value === '1year' ? 'Documents Created (Weekly)' : 'Documents Created',
          data: docActivityData.value.counts,
          fill: false,
          borderColor: '#1976d2',
          backgroundColor: '#1976d2',
          tension: 0.3,
          pointRadius: activityPeriod.value === '1year' ? 2 : 3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true, position: 'top' }
        },
        scales: {
          y: { beginAtZero: true, precision: 0 },
          x: {
            ticks: {
              maxTicksLimit: activityPeriod.value === '1year' ? 12 : undefined
            }
          }
        }
      }
    })
  })
}

onMounted(() => {
  renderDeptChart()
  renderDocActivityChart()
})

watch([() => props.users, () => props.departments], () => {
  renderDeptChart()
})

watch([() => props.documents, activityPeriod], () => {
  renderDocActivityChart()
})
</script>

<style scoped>
.custom-title {
  font-size: 28px;
  font-weight: 600;
  color: #333;
}
</style>

