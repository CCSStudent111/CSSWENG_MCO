<!-- LAST UPDATED BY: FRANZ -->

<template>
    <AppLayout>
        <div class="custom-title mb-4">Pending Documents</div>
        
        <v-table density="comfortable">
            <thead>
                <tr>
                    <th class="text-left">Document ID</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Type</th>
                    <th class="text-left">Tags</th>
                    <th class="text-left">Issued At</th>
                    <th class="text-left">Created By</th>
                    <th class="text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(document, index) in documents" :key="document.id"
                    :class="index % 2 === 0 ? 'bg-grey-lighten-4' : ''">
                    <td>{{ document.id }}</td>
                    <td>{{ document.name }}</td>
                    <td>{{ document.type.name }}</td>

                    <td>
                        <v-chip v-for="tag in document.tags" :key="tag.id" color="blue-lighten-3" size="small" label>
                            {{ tag.name }}
                        </v-chip>
                    </td>
                    <td>{{ dayjs(document.issued_at).format('MM/DD/YYYY') }}</td>
                    <td>{{ document.creator.username }}</td>
                    <td>
                        <Link :href="route('documents.show', document.id)">
                        <v-btn icon size="small" variant="text" aria-label="View">
                            <v-icon>mdi-eye</v-icon>
                        </v-btn>
                        </Link>

                        <Link :href="route('documents.edit', document.id)">
                        <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        </Link>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'

const form = useForm({})

const props = defineProps({
    documents: Array,
})

</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}

</style>
