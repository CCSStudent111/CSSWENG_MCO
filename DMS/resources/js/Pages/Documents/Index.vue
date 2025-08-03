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
            <template v-if="authUser.role !== 'Employee' || authUser.is_admin">
                <Link :href="route('documents.trash')">
                <v-btn color="error" variant="flat" size="small">
                    <v-icon start>mdi-delete</v-icon>Trash
                </v-btn>
                </Link>
            </template>
            <Link :href="route('documents.create')">
            <v-btn color="primary" variant="flat" size="small">
                <v-icon start>mdi-upload</v-icon>Upload Document
            </v-btn>
            </Link>
        </div>
        <div class="controls-row mb-4">
            <div class="d-flex mb-4" style="gap: 16px;">
                <v-select v-model="entries" :items="entriesOptions" label="Show entries" class="custom-entries"
                    density="compact" hide-details variant="outlined" style="width: 160px; flex-shrink: 0;"></v-select>

                <v-text-field v-model="search" label="Search Documents" prepend-inner-icon="mdi-magnify" clearable
                    density="compact" class="custom-search" style="flex: 1 1 0;" variant="outlined" />

                <v-select v-model="selectedType" :items="props.documentTypes" item-title="name" item-value="id"
                    label="Filter by Type" clearable density="compact" class="custom-entries" hide-details
                    variant="outlined" style="width: 200px; flex-shrink: 0;" />

                <v-select v-model="selectedTags" :items="props.tags" item-title="name" item-value="id" label="Filter by Tags"
                    multiple chips clearable density="compact" class="custom-entries" hide-details variant="outlined"
                    style="width: 200px; flex-shrink: 0;" />

                <v-select v-model="sortBy" :items="[
                    { title: 'ID (Low to High)', value: 'id-asc' },
                    { title: 'ID (High to Low)', value: 'id-desc' },
                    { title: 'Name (A-Z)', value: 'name-asc' },
                    { title: 'Date (Newest)', value: 'date-desc' }
                ]" label="Sort by" density="compact" variant="outlined" hide-details
                    style="width: 100px; flex-shrink: 0;" />
            </div>
            <div class="d-flex" style="gap: 16px;">
                <v-text-field v-model="startDate" label="Start Date" type="date" density="compact" hide-details
                    variant="outlined" style="max-width: 170px" />
                <v-text-field v-model="endDate" label="End Date" type="date" density="compact" hide-details
                    variant="outlined" style="max-width: 170px" />
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
                    <th class="text-center">Actions</th>
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
                    <td class="text-center" >
                        <Link :href="route('documents.show', document.id)">
                        <v-btn icon size="small" variant="text" aria-label="View">
                            <v-icon>mdi-eye</v-icon>
                        </v-btn>
                        </Link>
                        <template v-if="authUser.role !== 'Employee' || authUser.is_admin">
                            <Link :href="route('documents.edit', document.id)">
                            <v-btn icon size="small" color="primary" variant="text" aria-label="Edit">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            </Link>
                            <v-btn icon size="small" color="error" variant="text" aria-label="Delete"
                                @click="deleteDocument(document.id)">
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </template>

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
import { Link, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import dayjs from 'dayjs'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore'

const { props: pageProps } = usePage()
const authUser = pageProps.auth.user

dayjs.extend(isSameOrAfter)
dayjs.extend(isSameOrBefore)

const props = defineProps({
    documents: { type: Array, default: () => [] },
    documentTypes: { type: Array, default: () => [] },
    tags: { type: Array, default: () => [] },
})

const entries = ref(10)
const entriesOptions = [5, 10, 25, 50, 100]
const search = ref('')
const page = ref(1)
const selectedType = ref(null)
const selectedTags = ref([])
const startDate = ref('')
const endDate = ref('')
const sortBy = ref('')

const form = useForm({})

function deleteDocument(id) {
    if (confirm('Delete this document?')) {
        form.delete(route('documents.destroy', id), {
            preserveScroll: true
        })
    }
}

const filteredDocuments = computed(() => {
    if (!props.documents || !Array.isArray(props.documents)) {
        return []
    }

    const sortedDocs = [...props.documents].sort((a, b) => b.id - a.id)

    return sortedDocs.filter(doc => {
        if (!doc || !doc.type || !doc.creator) {
            return false
        }

        const issuedAt = dayjs(doc.issued_at)

        // Search filter
        const searchTerm = search.value.toLowerCase()
        const matchesSearch = !searchTerm ||
            doc.name?.toLowerCase().includes(searchTerm) ||
            doc.type?.name?.toLowerCase().includes(searchTerm) ||
            String(doc.id).includes(searchTerm)

        // Type filter
        const matchesType = !selectedType.value || doc.type.id === selectedType.value

        // Date filters
        let matchesStartDate = true
        let matchesEndDate = true

        if (startDate.value && issuedAt.isValid()) {
            const start = dayjs(startDate.value)
            matchesStartDate = start.isValid() && issuedAt.isSameOrAfter(start, 'day')
        }

        if (endDate.value && issuedAt.isValid()) {
            const end = dayjs(endDate.value)
            matchesEndDate = end.isValid() && issuedAt.isSameOrBefore(end, 'day')
        }

        const matchesTags = selectedTags.value.length === 0 ||
        selectedTags.value.every(tagId => doc.tags?.some(tag => tag.id === tagId))

        return matchesSearch && matchesType && matchesStartDate && matchesEndDate && matchesTags
    })
})

const sortedDocuments = computed(() => {
    const docs = [...filteredDocuments.value]

    if (sortBy.value === 'id-asc') {
        docs.sort((a, b) => a.id - b.id)
    } else if (sortBy.value === 'id-desc') {
        docs.sort((a, b) => b.id - a.id)
    } else if (sortBy.value === 'name-asc') {
        docs.sort((a, b) => a.name.localeCompare(b.name))
    } else if (sortBy.value === 'date-desc') {
        docs.sort((a, b) => dayjs(b.issued_at).unix() - dayjs(a.issued_at).unix())
    }

    return docs
})

const pageCount = computed(() => {
    return Math.ceil(sortedDocuments.value.length / entries.value) || 1
})

const paginatedDocuments = computed(() => {
    const start = (page.value - 1) * entries.value
    return sortedDocuments.value.slice(start, start + entries.value)
})


// Reset pagination 
watch([entries, selectedType, startDate, endDate, search, sortBy], () => {
    page.value = 1
})
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
    min-width: 100px;
    max-width: 180px;
    height: 40px;
    align-items: center;
}

.custom-search {
    width: 100%;
    height: 40px;
}

.narrow-select {
    max-width: 90px !important;
    min-width: 90px !important;
}
</style>
