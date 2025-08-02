<template>
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
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'

const props = defineProps({
  documents: { type: Array, required: true }
})

const activityPeriod = ref('30days')
const periodOptions = [
  { title: 'Last 7 Days', value: '7days' },
  { title: 'Last 30 Days', value: '30days' },
  { title: 'Last Year', value: '1year' }
]

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
  renderDocActivityChart()
})

watch([() => props.documents, activityPeriod], () => {
  renderDocActivityChart()
})
</script>