<template>
    <AppLayout>
        <div class="custom-title mb-4">Activity Logs for "{{ document.name }}"</div>

        <v-table density="comfortable">
            <thead>
                <tr>
                    <th class="text-left">Description</th>
                    <th class="text-left">Changes</th>
                    <th class="text-left">Updated By</th>
                    <th class="text-left">Date</th>
                    <th class="text-left">Time</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(log, index) in logs"
                    :key="index"
                    :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''"
                >
                    <td>{{ log.description }}</td>
                    <td>
                        <div v-if="log.changes.attributes">
                            <strong>New:</strong>
                            <ul>
                                <li v-for="(value, key) in log.changes.attributes" :key="key">
                                    {{ key }}:
                                    <span v-if="key === 'issued_at'">
                                        {{ formatDate(value) }}
                                    </span>
                                    <span v-else>
                                        {{ value }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div v-if="log.changes.old">
                            <strong>Old:</strong>
                            <ul>
                                <li v-for="(value, key) in log.changes.old" :key="key">
                                    {{ key }}:
                                    <span v-if="key === 'issued_at'">
                                        {{ formatDate(value) }}
                                    </span>
                                    <span v-else>
                                        {{ value }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td>{{ log.causer?.username ?? 'System' }}</td>
                    <td>{{ formatDate(log.date) }}</td>
                    <td>{{ formatTime(log.date) }}</td>
                </tr>
            </tbody>
        </v-table>
    </AppLayout>
</template>

<script setup>
import { defineProps } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'

const props = defineProps({
    document: Object,
    logs: Array
})

function formatDate(date) {
    return dayjs(date).format('MM/DD/YYYY')
}

function formatTime(date) {
    return dayjs(date).format('hh:mm A')
}
</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}
</style>
