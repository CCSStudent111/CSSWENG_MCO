<!-- LAST UPDATED BY: FRANZ -->

<template>
    <AppLayout>
        <div class="custom-title mb-4">Manage Documents</div>
        <div class="d-flex mb-4 justify-end gap-2">
            <Link :href="route('documents.all-logs')">
            <v-btn color="secondary" variant="flat" size="small">
                <v-icon start>mdi-history</v-icon>Logs
            </v-btn>
            </Link>
            <Link :href="route('documents.trash')">
            <v-btn color="error" variant="flat" size="small">
                <v-icon start>mdi-delete</v-icon>Trash
            </v-btn>
            </Link>
            <Link :href="route('documents.create')">
            <v-btn color="primary" variant="flat" size="small">
                <v-icon start>mdi-upload</v-icon>Upload Document
            </v-btn>
            </Link>
        </div>
        <div class="controls-row mb-4">
            <div class="d-flex" style="gap: 16px;">
                <v-select
                    v-model="entries"
                    :items="entriesOptions"
                    label="Show entries"
                    class="custom-entries"
                    density="compact"
                    hide-details
                    variant="outlined"
                    style="width: 160px; flex-shrink: 0;"
                ></v-select>
                <v-text-field
                    v-model="search"
                    label="Search Documents"
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    density="compact"
                    class="custom-search"
                    style="flex: 1 1 0;"
                ></v-text-field>
            </div>
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
                    <th class="text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(document, index) in paginatedDocuments" :key="document.id"
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
                        <v-btn icon size="small" color="error" variant="text" aria-label="Delete"
                            @click="deleteDocument(document.id)">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>

                    </td>
                </tr>
            </tbody>
        </v-table>
        <div class="d-flex justify-end align-center mt-4">
            <v-pagination v-model="page" :length="pageCount" total-visible="7" color="primary"></v-pagination>
            <span class="ml-4">Page {{ page }} of {{ pageCount }}</span>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'

const entries = ref(10)
const entriesOptions = [5, 10, 25, 50, 100]
const search = ref('')
const page = ref(1)

const form = useForm()


const props = defineProps({
    documents: Array
})

function deleteDocument(id) {
    if (confirm('Delete this document?')) {
        form.delete(route('documents.destroy', id), {
            preserveScroll: true
        })
    }
}

const filteredDocuments = computed(() =>
    props.documents.filter(doc =>
        doc.name.toLowerCase().includes(search.value.toLowerCase()) ||
        doc.type.name.toLowerCase().includes(search.value.toLowerCase()) ||
        String(doc.id).includes(search.value)
    )
)

const pageCount = computed(() =>
    Math.ceil(filteredDocuments.value.length / entries.value)
)

const paginatedDocuments = computed(() => {
    const start = (page.value - 1) * entries.value
    return filteredDocuments.value.slice(start, start + entries.value)
})

watch(entries, () => { page.value = 1 })

// Reset to page 1 when entries per page changes
watch(entries, () => { page.value = 1 })
</script>

<style scoped>
.custom-title {
    font-size: 2.5rem;
    font-weight: bold;
}

.controls-row {
    width: 100%;
    max-width: 100%;
}

.custom-entries {
    min-width: 140px;
    max-width: 180px;
    height: 40px;
    align-items: center;
}

.custom-search {
    width: 100%;
    height: 40px;
}
</style>
