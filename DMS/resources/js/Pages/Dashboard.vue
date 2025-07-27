<template>
  <v-app>
    <!-- Sidebar Navigation as Rail -->
    <v-navigation-drawer
      app
      permanent
      color="primary"
      dark
      width="80"
      class="pa-2"
    >
      <v-list nav dense>
        <Link href="/" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Dashboard" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/documents" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Documents" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-file-document</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/clients" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Clients" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-account-group</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <Link href="/users" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Users" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
        <v-divider class="my-2"></v-divider>
        <Link href="/logout" method="post" as="button" style="text-decoration: none; color: inherit;">
          <v-tooltip text="Logout" location="right">
            <template #activator="{ props }">
              <v-list-item v-bind="props" class="justify-center">
                <v-list-item-icon>
                  <v-icon>mdi-logout</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </template>
          </v-tooltip>
        </Link>
      </v-list>
    </v-navigation-drawer>

    <!-- Top App Bar -->
    <v-app-bar app color="primary" dark>
      <v-toolbar-title>Dashboard</v-toolbar-title>
    </v-app-bar>

    <!-- Main Content -->
    <v-main>
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
              <div class="text-h6 mb-4">Document Activity (Last 30 Days)</div>
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
    </v-main>


  </v-app>

</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  documents: { type: Array, default: () => [] },
  clients: { type: Array, default: () => [] },
  users: { type: Array, default: () => [] },
  departments: { type: Array, default: () => [] },
  currentUser: { type: Object, default: () => ({}) }
})

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

// --- Document Activity Over the Past Month (Line Chart) ---
const docActivityChart = ref(null)
let docActivityChartInstance = null

// Prepare data: count documents created per day for the last 30 days
const docActivityData = computed(() => {
  const days = []
  const counts = []
  const now = new Date()
  for (let i = 29; i >= 0; i--) {
    const d = new Date(now)
    d.setDate(now.getDate() - i)
    const label = `${d.getMonth() + 1}/${d.getDate()}`
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
          label: 'Documents Created',
          data: docActivityData.value.counts,
          fill: false,
          borderColor: '#1976d2',
          backgroundColor: '#1976d2',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true, position: 'top' }
        },
        scales: {
          y: { beginAtZero: true, precision: 0 }
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
watch(() => props.documents, () => {
  renderDocActivityChart()
})
</script>

