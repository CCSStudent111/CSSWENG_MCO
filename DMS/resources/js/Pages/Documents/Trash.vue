<template>
    <AppLayout>
        <div class="custom-title mb-4">Trashed Documents</div>

        <div class="d-flex mb-4 justify-end gap-2">
            <Link :href="route('documents.index')">
            <v-btn color="primary" variant="flat" size="small">
                <v-icon start>mdi-arrow-left</v-icon>Back to Documents
            </v-btn>
            </Link>
        </div>

        <v-table density="comfortable">
            <thead>
                <tr>
                    <th class="text-left">Document ID</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Type</th>
                    <th class="text-left">Tags</th>
                    <th class="text-left">Issued At</th>
                    <th class="text-left">Created By</th>
                    <th class="text-center">Actions</th>
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
                    <td>{{ document.creator?.username ?? 'Unknown' }}</td>

                    <td class="text-center">
                        <v-btn icon size="small" color="success" variant="text" aria-label="Restore"
                            @click="restoreDocument(document.id)">
                            <v-icon>mdi-restore</v-icon>
                        </v-btn>

                        <v-btn icon size="small" color="error" variant="text" aria-label="Delete Permanently"
                            @click="forceDeleteDocument(document.id)">
                            <v-icon>mdi-delete-forever</v-icon>
                        </v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'

const props = defineProps({
    documents: Array
})

function restoreDocument(id) {
    router.put(route('documents.restore', id), {}, {
        preserveScroll: true,
        onSuccess: () => console.log('Restored')
    })
}

function forceDeleteDocument(id) {
    if (confirm('Are you sure you want to permanently delete this document?')) {
        router.delete(route('documents.forceDelete', id), {
            preserveScroll: true,
            onSuccess: () => console.log('Deleted')
        })
    }
}
</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}
</style>
