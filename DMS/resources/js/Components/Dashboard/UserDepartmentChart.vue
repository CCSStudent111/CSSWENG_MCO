<template>
  <v-card class="pa-6 mb-4 h-100" outlined>
    <div class="text-h6 mb-2">Users per Department</div>
    <canvas ref="deptChart" height="180"></canvas>
  </v-card>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'

const props = defineProps({
  users: { type: Array, required: true },
  departments: { type: Array, required: true }
})

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

onMounted(() => {
  renderDeptChart()
})

watch([() => props.users, () => props.departments], () => {
  renderDeptChart()
})
</script>