<template>
    <AppLayout>
        <div class="custom-title mb-4">Manage Users</div>

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
                <tr v-for="(user, index) in users" :key="user.id" :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
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
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'

const props = defineProps({
    users: Array
})
</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}
</style>
